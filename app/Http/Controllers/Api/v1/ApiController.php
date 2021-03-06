<?php
namespace X10WeaponStatsApi\Http\Controllers\Api\v1;

use X10WeaponStatsApi\Http\Controllers\Controller;
use X10WeaponStatsApi\Models\Config;
use X10WeaponStatsApi\Models\Person;
use X10WeaponStatsApi\Models\WeaponInstance;
use X10WeaponStatsApi\Models\WeaponInstanceAttribute;
use X10WeaponStatsApi\Models\Attribute;
use X10WeaponStatsApi\Models\Weapon;
use Illuminate\Support\Facades\Schema;

class ApiController extends Controller
{

    public function getGraphList()
    {
        return \response()->json([
            'generated_at' => (new \DateTime)->format('Y-m-d H:i:s'),
            'results' => [
                'configs' => $this->getObjectGraph()->toArray()
            ]
            
        ]);
        
    }
    
    public function getAttributeList()
    {
    	$attributes = Attribute::all([
    		'defindex',
    		'name',
    		'attribute_class',
    		'description_string',
    		'description_format',
    		'effect_type',
    		'hidden',
    		'stored_as_integer'
    	]);
    	
    	return \response()->json([
    		'generated_at' => (new \DateTime)->format('Y-m-d H:i:s'),
    		'results' => [
    			'attributes' => $attributes->toArray()
    		]
    	]);
    }
    
    public function getWeaponList()
    {
    	$weapons = Weapon::all([
    		'defindex',
    		'name',
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
    	]);
    	
    	return \response()->json([
    		'generated_at' => (new \DateTime)->format('Y-m-d H:i:s'),
    		'results' => [
    			'weapons' => $weapons->toArray()
    		]
    	]);
    }
    
    protected function removeProps($collection, $props) {
        foreach($props as $prop) {
            foreach($collection as $key => $item) {
                unset($collection[$key][$prop]);
            }
        }
    }
    
    protected function getObjectGraph() {
        $configs = Config::with('people.weaponInstances')->get();
        
        $query = \DB::table('weapon_instance_attributes')
        ->select([
            'weapon_instance_attributes.weapon_instance_id',
            'weapon_instance_attributes.attribute_defindex',
            'weapon_instance_attributes.attribute_value',

        ]);
        
        $attrInstances = $query->get();
        
        $attrMap = [];
        
        foreach($attrInstances as $attrInstance) {
            if(!array_key_exists($attrInstance->weapon_instance_id, $attrMap)) {
                $attrMap[$attrInstance->weapon_instance_id] = [];
            }
             
            $attrMap[$attrInstance->weapon_instance_id][] = $attrInstance;
        }
        
        $query = \DB::table('weapons')->select(['weapons.defindex','weapons.name']);
        $weaponNames = $query->get();
        
        $weaponNameMap = [];
        
        foreach($weaponNames as $weapon) {
            $weaponNameMap[$weapon->defindex] = $weapon->name;
        }
        
        $this->removeProps($configs, ['created_at', 'updated_at']);
        
        $configs->each(function($config) use($attrMap, $weaponNameMap) {
            $this->removeProps($config->people, ['created_at', 'updated_at', 'config_id']);
            $config->people->each(function($person) use($attrMap, $weaponNameMap) {
                $this->removeProps($person->weaponInstances, ['created_at', 'updated_at', 'person_id']);
        
                $person->weaponInstances->each(function($weapon_instance) use($attrMap, $weaponNameMap) {
        
                    //$weapon_instance->name = $weaponNameMap[$weapon_instance->weapon_defindex];
        
                    if(array_key_exists($weapon_instance->id, $attrMap)) {
                        $weapon_instance->weapon_instance_attributes = $attrMap[$weapon_instance->id];
                    }
                    else {
                        $weapon_instance->weapon_instance_attributes = [];
                    }
                });
            });
        });
        
        return $configs;
    }
    
    public function sampleOutput()
    {
        $contents = file_get_contents(__DIR__ . '/../../../../Console/Commands/get_schema_example_output.json');
        
        $object = json_decode($contents, true);
        
        $results = $object['result']['items'][30];
        
        
        return \response()->json($results);
    }
}











