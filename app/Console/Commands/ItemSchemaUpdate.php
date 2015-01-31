<?php namespace X10WeaponStatsApi\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;

use X10WeaponStatsApi\Models\Weapon;

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
	    $this->info('Getting item schema from steam...');
	    $schema = $this->getItemSchema()['result'];
	    $this->info("  Done");
	
 		$this->bulkInsert('X10WeaponStatsApi\Models\Weapon', $schema['items']);
 		$this->bulkInsert('X10WeaponStatsApi\Models\Attribute', $schema['attributes']);
	}

	private function getItemSchema() {
	    $STEAM_GET_SCHEMA_URL = 'http://api.steampowered.com/IEconItems_440/GetSchema/v0001?language=en_US&key=' . env('STEAM_WEB_API_KEY');
		
	    $file_contents = file_get_contents($STEAM_GET_SCHEMA_URL);
	    //$file_contents = file_get_contents(__DIR__ . "/get_schema_example_output.json");
		
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
	    $props = $props ?: (new $class_name)->fillable;
	    
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
		$this->info("Inserting $count row(s) into  $table...");
		
		// Were chunking things because if its the first load and were trying to insert
		// 3000 weapons the SQL gets pretty darn long and sometimes it fails (on SQLite)
		// -- deviousfrog 2015-01-31
		foreach(array_chunk($inserts, 500) as $chunk) {
		    \DB::table($table)->insert($chunk);
		}
		
		$this->info("  Done");
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
