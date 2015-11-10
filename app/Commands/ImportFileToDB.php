<?php namespace X10WeaponStatsApi\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use X10WeaponStatsApi\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

use X10WeaponStatsApi\Helpers\VDFTree\VDFTree;

use X10WeaponStatsApi\Models\Config;
use X10WeaponStatsApi\Models\Person;
use X10WeaponStatsApi\Models\WeaponInstance;
use X10WeaponStatsApi\Models\WeaponInstanceAttribute;

class ImportFileToDB extends Command implements SelfHandling {

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
		$people = $this->config->people();

//		foreach($people as $person) {
//			$weapon_instances = $person->weaponInstances;
//
//			foreach($weapon_instances as $weapon_instance) {
//				$weapon_instance->weaponInstanceAttributes()->delete();
//			}
//			$person->weaponInstances()->delete();
//		}

        \DB::transaction(function() {

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

}
