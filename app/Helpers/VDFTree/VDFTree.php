<?php

namespace X10WeaponStatsApi\Helpers\VDFTree;

use X10WeaponStatsApi\Models;


class VDFTree {
	
	private $file_name;
	private $file_contents;
	private $vdf_array;
	
	public $tree;
	
	public function __construct($file_name) {
		$this->file_name = $file_name;
		
		$this->file_contents = file_get_contents($file_name);
		
		$this->vdf_array = $this->vdfToArray();
		
		$this->tree = $this->parseToTree();
	}
	
	/*
	 * The following code was taken from https://gist.github.com/AlienHoboken/5571903
	 * and then modified
	 * It is linked to in this stackoverflow answer suggesting it can be used
	 * http://stackoverflow.com/a/21947808
	 */
	private function vdfToArray()
	{
		// load VDF data either from API call or fetching from file/url
		// no matter your method, $json must contain the VDF data to be parsed
		$json = $this->file_contents;
	
		// encapsulate in braces
		$json = "{\n$json\n}";
	
	
		// remove comments
		$pattern = '~([^/]*)//.*~';
		$replace = '$1';
		$json = preg_replace($pattern, $replace, $json);
	
		// replace open braces
		$pattern = '/"([^"]*)"(\s*){/';
		$replace = '"${1}": {';
		$json = preg_replace($pattern, $replace, $json);
	
		// replace values
		$pattern = '~"([^"]*)"\s*"([^"]*)"(.*)~';
		$replace = '"${1}": "${2}",';
		$json = preg_replace($pattern, $replace, $json);
	
		// remove trailing commas
		$pattern = '/,(\s*[}\]])/';
		$replace = '${1}';
		$json = preg_replace($pattern, $replace, $json);
	
		// add commas
		$pattern = '/([}\]])(\s*)("[^"]*":\s*)?([{\[])/';
		$replace = '${1},${2}${3}${4}';
		$json = preg_replace($pattern, $replace, $json);
	
		// object as value
		$pattern = '/}(\s*"[^"]*":)/';
		$replace = '},${1}';
		$json = preg_replace($pattern, $replace, $json);
	
		$array = json_decode($json, true);
	
		if (!is_array($array)) {
			throw new \Exception('we don\'t have an array');
		}
	
		return $array;
	}
	
	private function parseToTree() {
		$arr = $this->vdf_array;
		
		//dd($arr);
		
		if(count($arr) != 1) {
			throw new VDFParseException\VDFParseException();
		}
		
		$people = $arr['custom_weapons_v3'];
		
		$people_tree = [];
		
		foreach($people as $person_name => $weapons) {
			$person = new Person();
			$person->name = $person_name;
			$person->weapons = [];
			
			foreach($weapons as $weapon_id => $attributes) {
				if(is_numeric($weapon_id)) {
					$weapon = new Weapon();
					$weapon->id = $weapon_id;
					
					foreach($attributes as $attribute_key => $attribute_str) {
						if(is_numeric($attribute_key)) {
							$exploded = explode(';', $attribute_str);
					
							$attribute = new Attribute();
							$attribute->id = trim($exploded[0]);
							$attribute->value = trim($exploded[1]);
					
							$weapon->attributes[] = $attribute;
						}
					}
					$person->weapons[] = $weapon;
				}
			}
			$people_tree[] = $person;
			
		}
		
		$this->tree = $people_tree;
	}

}