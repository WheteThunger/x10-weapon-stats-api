<?php namespace X10WeaponStatsApi\Commands;

use Illuminate\Support\Collection;
use X10WeaponStatsApi\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

use X10WeaponStatsApi\Helpers\VDFTree\VDFTree;

use X10WeaponStatsApi\Models\Config;
use X10WeaponStatsApi\Models\Person;
use X10WeaponStatsApi\Models\WeaponInstance;
use X10WeaponStatsApi\Models\WeaponInstanceAttribute;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\DatabaseManager;

class ImportFileToDB extends Command implements SelfHandling
{

    private $tree;
    private $config;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(VDFTree $tree, Config $config)
    {
        $this->tree = $tree;
        $this->config = $config;

    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->deleteConfigData();

        $this->insertTree();

    }

    private function deleteConfigData()
    {
        \DB::transaction(function () {

            $people_ids = $this->config
                ->people()
                ->getEager()
                ->lists("id");

            $weapon_instance_ids = WeaponInstance
                ::whereIn("person_id", $people_ids)
                ->get(["id"])
                ->lists("id");

            $weapon_inst_attr_ids = WeaponInstanceAttribute
                ::whereIn("weapon_instance_id", $weapon_instance_ids)
                ->get(["id"])
                ->lists("id");

            WeaponInstanceAttribute::destroy($weapon_inst_attr_ids);
            WeaponInstance::destroy($weapon_instance_ids);
            Person::destroy($people_ids);
        });
    }

    private function insertTree()
    {

        $now = date('Y-m-d H:i:s'); // Will result in slightly incorrect timestamps. Don't care.

        $inserts_instance_attribute = [];

        foreach ($this->tree->getVDFArray()["custom_weapons_v3"] as $steam_id => $weapons) {

            $person = new Person();
            $person->config_id = $this->config->id;
            $person->name = $steam_id;
            $person->tf2items_id = $steam_id;

            $person->save();

            foreach ($weapons as $weapon_defindex => $attributes) {
                $weapon = new WeaponInstance();
                $weapon->person_id = $person->id;
                $weapon->weapon_defindex = $weapon_defindex;
                $weapon->save();

                foreach ($attributes as $attr_key => $attribute_string) {
                    if (is_numeric($attr_key)) {
                        $attr_array = explode(';', $attribute_string);

                        if (count($attr_array) != 2) {
                            throw new \DomainException("Unable to parse the following attribute string: `$attribute_string`");
                        }

                        $attr_defindex = trim($attr_array[0]);
                        $attr_value = trim($attr_array[1]);

                        $inserts_instance_attribute[] = [
                            "weapon_instance_id" => $weapon->id,
                            "attribute_defindex" => $attr_defindex,
                            "attribute_value" => $attr_value,
                            "updated_at" => $now,
                            "created_at" => $now
                        ];
                    }
                }
            }
        }

        // Bulk inserts because faster.
        WeaponInstanceAttribute::insert($inserts_instance_attribute);


    }

}
