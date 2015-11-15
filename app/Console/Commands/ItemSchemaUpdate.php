<?php
namespace X10WeaponStatsApi\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use X10WeaponStatsApi\Models\Attribute;
use X10WeaponStatsApi\Models\Weapon;
use X10WeaponStatsApi\Models\TF2Class;
use X10WeaponStatsApi\Models\Config;
use X10WeaponStatsApi\Models\Person;
use X10WeaponStatsApi\Models\WeaponInstance;

class ItemSchemaUpdate extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'rxg-x10:itemschema-update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Pull in the item schema and update the db';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

    public function write($text) {
        $this->output->write("<info>$text</info>");
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
        // Speed up mysql inserts for easier developement.
        \DB::unprepared("SET autocommit=0; SET unique_checks=0; foreign_key_checks=0;");
        $table = $this->argument('table');

		$this->write('Getting item schema from steam...');
		$schema = $this->getItemSchema()['result'];
		$this->info("  Done");

		$weapons = new Collection($schema['items']);

		$weapons = $weapons->toArray();

		if ($table === 'weapon' || $table === 'all') {
			$this->write("Inserting new weapons...");
			$this->bulkInsert('X10WeaponStatsApi\Models\Weapon', $weapons);
			$this->info("  Done");
		}

		if ($table === 'attribute' || $table === 'all') {
			$this->write("Inserting new attributes...");
			$this->bulkInsert('X10WeaponStatsApi\Models\Attribute', $schema['attributes']);
			$this->info("  Done");
		}

		if ($table === 'class-weapon' || $table === 'all') {
			$this->write("Updating class-weapon relationships...");
			$this->refreshClassWeaponRelations($schema);
			$this->info("  Done");
		}

		if ($table === 'weapon-instance' || $table === 'all') {
			$this->write("Inserting new weaponInstances...");
			$this->refreshWeaponInstances($weapons);
			$this->info("  Done");
		}

		if ($table === 'weapon-instance-attribute' || $table === 'all') {
			$this->write("Inserting new weapon instance attributes...");
			$this->refreshWeaponAttributes($weapons);
			$this->info("  Done");
		}

        \DB::unprepared("COMMIT; SET unique_checks=1; foreign_key_checks=1;");
	}

	private function getItemSchema() {
		$STEAM_GET_SCHEMA_URL = 'http://api.steampowered.com/IEconItems_440/GetSchema/v0001?language=en_US&key=' . env('STEAM_WEB_API_KEY');

		//$file_contents = file_get_contents($STEAM_GET_SCHEMA_URL);
		$file_contents = file_get_contents(__DIR__ . "/get_schema_example_output.json");

		return json_decode($file_contents, true);
	}

	/*
	 * Inserts any entries that don't already exist in our database.
	 * This function should work as long as the entities primary key exists in
	 * the item schema.
	 *
	 * This function assumes that the entity being inserted has created_at
	 * and updated_at fields
	 */
	private function bulkInsert($class_name, array $raw_items, array $props = null) {
		$pk = (new $class_name())->getKeyName();
		$table = (new $class_name())->getTable();
		$props = $props ?: (new $class_name())->getFillable();

		// Get an array of existing primary keys
		$existing_weapons = $class_name::all()->map(function ($weapon) use($pk) {
			return $weapon->$pk;
		})->toArray();

		$raw_collection = new Collection($raw_items);

		// We only want entries that don't exist in our database
		$raw_collection = $raw_collection->filter(function ($raw_item) use($existing_weapons, $pk) {
			return ! in_array($raw_item[$pk], $existing_weapons);
		});

		$inserts = [];

		foreach ($raw_collection as $raw_item) {
			$to_insert = [];

			foreach ($props as $prop) {
				$to_insert[$prop] = isset($raw_item[$prop]) ? $raw_item[$prop] : "";
			}

			$to_insert['created_at'] = (new \DateTime())->format('Y-m-d H:i:s');
			$to_insert['updated_at'] = $to_insert['created_at'];

			$inserts[] = $to_insert;
		}

		$count = count($inserts);

        \DB::table($table)->insert($inserts);
	}

	/*
	 * Delete all entries in the table class_weapon and reinsert
	 */
	protected function refreshClassWeaponRelations($schema) {
		$schema_items = new Collection($schema['items']);

		$schema_weapons = $schema_items->filter(function ($item) {
			return isset($item['item_class']) && Str::startsWith($item['item_class'], 'tf_weapon') && isset($item['used_by_classes']);
		});

		$classes = TF2Class::all();

		$to_insert = new Collection();

		$schema_weapons->each(function ($schema_weapon) use($classes, $to_insert) {
			$used_class_ids = $classes->filter(function ($class) use($schema_weapon) {
				$used_by = array_map('strtolower', $schema_weapon['used_by_classes']);
				return in_array($class->name, $used_by);
			})
				->map(function ($class) {
				return $class->id;
			})
				->toArray();

			foreach ($used_class_ids as $class_id) {
				$to_insert->push([
					'weapon_defindex' => $schema_weapon['defindex'],
					'class_id' => $class_id
				]);
			}
		});

		\DB::table('class_weapon')->delete();

		\DB::table('class_weapon')->insert($to_insert->toArray());
	}

	protected function refreshWeaponInstances($weapons) {
		$everyone_person_id = $this->getEveryonePersonId();

		$to_insert = [];

		foreach ($weapons as $weapon) {
			$now = (new \DateTime())->format('Y-m-d H:i:s');

			$to_insert[] = [
				'person_id' => $everyone_person_id,
				'weapon_defindex' => $weapon["defindex"],
				'created_at' => $now,
				'updated_at' => $now
			];
		}

		\DB::table('weapon_instance')->delete();

        try {
            \DB::table('weapon_instance')->insert($to_insert);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
	}

	protected function refreshWeaponAttributes($weapons) {
		$everyone_person_id = Person::where([
			'tf2items_id' => '*'
		])->firstOrFail()->id;
		$attribute_map = [];

		foreach (Attribute::all() as $key => $attribute) {
			$attribute_map[$attribute->name] = $attribute->defindex;
		}

		$to_insert = [];

		$weapons = array_filter($weapons, function ($weapon) {
			return array_key_exists('attributes', $weapon);
		});

		foreach ($weapons as $weapon) {
			foreach ($weapon['attributes'] as $attribute) {
				$weapon_instance_id = WeaponInstance::where([
					'weapon_defindex' => $weapon['defindex'],
					'person_id' => $everyone_person_id
				])->first()->id;

				$attribute_defindex = $attribute_map[$attribute['name']];

				$now = (new \DateTime())->format('Y-m-d H:i:s');

				$to_insert[] = [
					'weapon_instance_id' => $weapon_instance_id,
					'attribute_defindex' => $attribute_defindex,
					'attribute_value' => $attribute['value'],
					'created_at' => $now,
					'updated_at' => $now
				];
			}
		}

		\DB::table('weapon_instance_attributes')->delete();

		\DB::table('weapon_instance_attributes')->insert($to_insert);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
			[
				'table',
				InputArgument::OPTIONAL,
				'desc',
				'all'
			]
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [];
	}

	private function getEveryonePersonId() {
		$everyone_person = Person::where([
			'tf2items_id' => '*'
		])->first();

		if($everyone_person === null) {
			$master_config_id;

			try {
				$master_config_id = Config::where([
					'name' => Config::MASTER_NAME
				])->firstOrfail()->id;
			}
			catch(\Exception $e) {
				if($e instanceof ModelNotFoundException) {
					$this->error("Couldn't find the master config! Tried to find a config named " . Config::MASTER_NAME);
				} else {
					$this->error($e->getMessage());
				}
				die();
			}

			$person = new Person();
			$person->name = "Everyone";
			$person->tf2items_id = "*";
			$person->config_id = $master_config_id;

			$person->save();

			return $person->id;
		}
		else {
			return $everyone_person->id;
		}

	}

}
