<?php namespace X10WeaponStatsApi\Commands;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use X10WeaponStatsApi\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use X10WeaponStatsApi\Models\Config;

class GenerateTf2ItemsFile extends Command implements SelfHandling
{

    /** @var Config */
    private $config;

    /**
     * Create a new command instance.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $base_arr = $this->generateConfigArray($this->config);



        $parent_config = Config::find($this->config->parent_id);

        while($parent_config !== null) {
            $parent_arr = $this->generateConfigArray($parent_config);

            $base_arr = $this->mergeConfigs($parent_arr, $base_arr);

            $parent_config = Config::find($parent_config->parent_id);
        }








        $vdf = $this->arrayToVDF($base_arr, "custom_weapons_v3");

        print_r($vdf);
    }

    private function arrayToVDF($obj, $key, $tab_level = 0) {
        $tabs = function($extra_level = 0) use ($tab_level) {
            $result = "";
            for($i = 0; $i < $tab_level+$extra_level; $i++) {
                $result .= "\t";
            }
            return $result;
        };

        $tab = $tabs();
        $result = "";

        if(is_array($obj)) {
            $result .= "$tab\"$key\"\n";
            $result .= "$tab{\n";
            foreach($obj as $key => $entry) {
                $result .= $this->arrayToVDF($entry, $key, $tab_level+1);
            }
            $result .= "$tab}\n";
            return $result;
        } else {
            $tab = $tabs();
            $result .= "$tab\"$key\"\t\"$obj\"\n";
            return $result;
        }

    }

    private function generateConfigArray(Config $config)
    {
        $people_entries = [];

        $people = $config->people()->get();

        foreach($people as $person) {
            $person_entry = [];

            $weapon_instances = $person->weaponInstances()->get();

            foreach($weapon_instances as $weapon_instance) {
                $instance_entry = [];

                $instance_attributes = $weapon_instance->weaponInstanceAttributes()->get();

                $count = 0;
                foreach($instance_attributes as $attribute_instance) {
                    $count++; //Starting at 1 on purpose.
                    $attr_defindex = $attribute_instance->attribute_defindex;
                    $attr_value = $attribute_instance->attribute_value;

                    $instance_entry[$count] = "$attr_defindex ; $attr_value";
                }

                $person_entry[$weapon_instance->weapon_defindex] = $instance_entry;
            }

            $people_entries[$person->tf2items_id] = $person_entry;
        }

        return $people_entries;
    }

    private function mergeConfigs(array $parent_arr, array $child_arr) {
        $final_arr = [];

        foreach($parent_arr as $parent_person_name => $parent_person) {
            $person_to_insert = null;

            foreach($child_arr as $child_person_name => $child_person) {
                if($parent_person_name === $child_person_name) {
                    $person_to_insert = $this->mergePeople($parent_person, $child_person);
                }
            }

            if($person_to_insert === null) {
                $person_to_insert = $parent_person;
            }

            $final_arr[$parent_person_name] = $person_to_insert;

        }

        return $final_arr;
    }

    private function mergePeople($parent_person, $child_person) {
        $person_to_insert = [];

        foreach($parent_person as $par_weapon_defindex => $par_attributes) {
            $weapon_to_insert = null;

            foreach($child_person as $chi_weapon_defindex => $chi_attributes) {
                if($par_weapon_defindex === $chi_weapon_defindex) {
                    $weapon_to_insert = $this->mergeWeapons($par_attributes, $chi_attributes);
                }
            }

            if($weapon_to_insert === null) {
                $weapon_to_insert = $par_attributes;
            }

            $person_to_insert[$par_weapon_defindex] = $weapon_to_insert;
        }

        return $person_to_insert;
    }

    // Not merging attributes on a single weapon
    // If you override anything on a weapon you must copy all attributes.
    private function mergeWeapons($parent_weapon, $child_weapon) {
        return $child_weapon;
//        $weapon_to_insert = [];
//
//
//
//        return $weapon_to_insert;
    }

}
