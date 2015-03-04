<?php namespace X10WeaponStatsApi\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use X10WeaponStatsApi\Models\Weapon;
use X10WeaponStatsApi\Models\TF2Class;

class ItemSchemaUpdate extends Command {
    
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'itemschema:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Pull in the item schema and update the db. Updates weapons, attributes, and class_weapon';
	
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
	    $this->info('Getting item schema from steam...');
	    $schema = $this->getItemSchema()['result'];
	    $this->info("  Done");
	    
	    $items = new Collection($schema['items']);
	    
	    $weapons = $items;
	    
// 	    $weapons = $weapons->filter(function($item) {
// 	        return isset($item['item_class'])
// 	        && Str::startsWith($item['item_class'], 'tf_weapon');
// 	    });
	    
	    $weapons = $weapons->toArray();
	    
	    $this->info("Inserting new weapons...");
 		$this->bulkInsert('X10WeaponStatsApi\Models\Weapon', $weapons);
 		$this->info("  Done");
 		
 		$this->info("Inserting new attributes...");
 		$this->bulkInsert('X10WeaponStatsApi\Models\Attribute', $schema['attributes']);
 		$this->info("  Done");
 		
 		$this->info("Updating class-weapon relationships...");
 		$this->refreshClassWeaponRelations($schema);
 		$this->info("  Done");
	}

	private function getItemSchema() {
	    $STEAM_GET_SCHEMA_URL = 'http://api.steampowered.com/IEconItems_440/GetSchema/v0001?language=en_US&key=' . env('STEAM_WEB_API_KEY');
		
	    //$file_contents = file_get_contents($STEAM_GET_SCHEMA_URL);
	    $file_contents = file_get_contents(__DIR__ . "/get_schema_example_output.json");
		
		return json_decode($file_contents, true);
	}
	
	/*
	 *  Inserts any entries that don't already exist in our database.
	 *  This function should work as long as the entities primary key exists in 
	 *  the item schema.
	 *  
	 *  This function assumes that the entity being inserted has created_at 
	 *  and updated_at fields
	 */
	private function bulkInsert($class_name, array $raw_items, array $props = null) {
	    $pk = (new $class_name)->getKeyName();
	    $table = (new $class_name)->getTable();
	    $props = $props ?: (new $class_name)->getFillable();
	    
	    // Get an array of existing primary keys
	    $existing_weapons = $class_name::all()
	       ->map(function($weapon) use ($pk) { 
	           return $weapon->$pk; 
	       })
	       ->toArray()
	    ;
	    	    
	    $raw_collection = new Collection($raw_items);
	    
	    // We only want entries that don't exist in our database
	    $raw_collection = $raw_collection->filter(function($raw_item) use($existing_weapons, $pk) {
 	        return !in_array($raw_item[$pk], $existing_weapons);
	    });
	    
	    
		$inserts = [];
		
		foreach($raw_collection as $raw_item) {
		    $to_insert = [];
		     
		    foreach($props as $prop) {
		        $to_insert[$prop] = isset($raw_item[$prop]) ? $raw_item[$prop] : "";
		    }
		    
		    $to_insert['created_at'] = (new \DateTime)->format('Y-m-d H:i:s');
		    $to_insert['updated_at'] = $to_insert['created_at'];
		     
		    $inserts[] = $to_insert;
		     
		}
		
		$count = count($inserts);
		
		// We're chunking things because if its the first load and we're trying to insert
		// 3000 weapons the SQL gets pretty darn long and sometimes it fails (on SQLite)
		// -- deviousfrog/bumble 2015-01-31
		foreach(array_chunk($inserts, 10) as $chunk) {
		    \DB::table($table)->insert($chunk);
		}
	}
	
	protected  function refreshClassWeaponRelations($schema) {
	    $schema_items = new Collection($schema['items']);
	    
	    $schema_weapons = $schema_items->filter(function($item) {
	        return isset($item['item_class']) 
	        && Str::startsWith($item['item_class'], 'tf_weapon')
	        && isset($item['used_by_classes']);
	    });
	    
	    $classes = TF2Class::all();
	    
	    $schema_weapons->each(function($schema_weapon) use($classes) {
	        $schema_uses_classes = new Collection($schema_weapon['used_by_classes']);
	        
            $used_classes = $classes->filter(function($class) use($schema_weapon) {
                $used_by = array_map('strtolower', $schema_weapon['used_by_classes']);
                return in_array($class->name, $used_by);
            });
	       
            $our_weapon = Weapon::findOrfail($schema_weapon['defindex']);
            $our_weapon->classes()->sync($used_classes);
            $our_weapon->save();
	    });
	    
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
