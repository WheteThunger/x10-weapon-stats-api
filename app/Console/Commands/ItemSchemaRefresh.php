<?php namespace X10WeaponStatsApi\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use X10WeaponStatsApi\Models\Weapon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\App;

class ItemSchemaRefresh extends Command {

	const STEAM_GET_SCHEMA_URL = 'http://api.steampowered.com/IEconItems_440/GetSchema/v0001?key=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'itemschema:refresh';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Pull in the item schema and update the db';

	private $weapon_props = [
	    'defindex',
	    'item_class',
	    'item_type_name',
	    'item_name',
	    'item_description',
	    'proper_name',
	    'item_slot',
	    'item_quality',
	    'image_url',
	    'image_url_large',
	    'min_ilevel',
	    'max_ilevel'
	];
	
	private $attribute_props = [
	    'name',
	    'defindex',
	    'attribute_class',
	    'description_string',
	    'description_format',
	    'effect_type',
	    'hidden',
	    'stored_as_integer'
	];
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$schema = $this->getItemSchema();
		
		//print_r(array_keys($item_schema['result']));
		//die();
		
// 	$this->refreshWeapons($item_schema['result']['items']);
// 	$this->refreshAttributes($item_schema['result']['attributes']);
		
		$this->bulkRefresh('weapons', $this->weapon_props, $schema['result']['items']);
		$this->bulkRefresh('attributes', $this->attribute_props, $schema['result']['attributes']);
	}

	private function getItemSchema() {
		//$json_data = json_decode(file_get_contents(self::STEAM_GET_SCHEMA_URL));
		
		// TEMP: While I'm developing the code
		$file_str = file_get_contents(__DIR__.'/get_schema_example_output.json');
		return json_decode($file_str, true);
	}
	
	private function bulkRefresh($table, array $props, array $raw_items) {
		
		$inserts = [];
		
		foreach($raw_items as $raw_item) {
		    $to_insert = [];
		     
		    foreach($props as $prop) {
		        $to_insert[$prop] = isset($raw_item[$prop]) ? $raw_item[$prop] : "";
		    }
		    $to_insert['created_at'] = (new \DateTime)->format('Y-m-d H:i:s');
		    $to_insert['updated_at'] = $to_insert['created_at'];
		     
		    $inserts[] = $to_insert;
		     
		}
		
		$this->info("Deleting all $table");
		\DB::table($table)->delete();
		$this->info("Done deleting $table");
		
		$this->info("Inserting rows $table");
		
		foreach(array_chunk($inserts, 500) as $chunk) {
		    \DB::table($table)->insert($chunk);
		}
		
		$this->info("Done inserting $table");
	}
	
	private function refreshAttributes($attributes) {
		
	}
	
	private function refreshWeapons($weapons) {
		

		
	}
	
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
// 		return [
// 			['example', InputArgument::REQUIRED, 'An example argument.'],
// 		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
// 		return [
// 			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
// 		];
	}

}
