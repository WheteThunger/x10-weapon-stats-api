<?php
require __DIR__ . '/../TestCase.php';

use X10WeaponStatsApi\Helpers\Tf2Items;

// class Tf2ItemsTest extends TestCase
// {

// 	public function testReadWriteSingleEntry() {
// 		$tf2items = Tf2Items::makeFromVDFFile(__DIR__ . '/vdf_files/single_tf2items_entry.vdf');
		
// 		// dd($tf2items);
		
// 		$party = $tf2items->first();
// 		$weapon = $party->value->first();
// 		$attribute = $weapon->value->first();
		
// 		$tests = [
// 			'X10WeaponStatsApi\Helpers\Tf2Items' => get_class($tf2items),
// 			'X10WeaponStatsApi\Helpers\Tf2ItemParty' => get_class($party),
// 			'*' => $party->party[0],
// 			'X10WeaponStatsApi\Helpers\Tf2ItemWeapon' => get_class($weapon),
// 			'886' => $weapon->defindex,
// 			'X10WeaponStatsApi\Helpers\Tf2ItemAttribute' => get_class($attribute),
// 			'96' => $attribute->defindex,
// 			'0.5' => $attribute->value,
// 			'' => $attribute->comment
// 		];
		
// 		foreach ($tests as $expected => $actual) {
// 			$this->assertEquals($expected, $actual);
// 		}
		
// 		$file = $tf2items->generateVDFFile();
		
// 		//dd($file);
		
// 		$expected = $this->normalizeOutput(file_get_contents(__DIR__ . '/vdf_files/single_tf2items_entry.vdf'));
		
// 		$actual = $this->normalizeOutput($file);
		
// 		$this->assertEquals($expected, $actual);
// 	}

// 	private function normalizeOutput($str) {
// 		$regexes = [
// 			'~([^/]*)//.*~' => '$1', // remove comments
// 			'~"([^"]*)"\s*"([^"]*)"(.*)~' => '"${1}": "${2}",', // Grab values
// 			'~[\s\n\t]+~' => "\n", // Replace all spaces with a new line for easier diffing
// 			'~"141"
// {
// "1":
// "136
// ;
// 1",
// "2":
// "15
// ;
// 1",
// "3":
// "3
// ;
// 0.75",
// }
// ~' => ''
// 		]; // There is an error in the example tf2items file. It references 141 twice, so were just removing it.

		
// 		foreach ($regexes as $pattern => $replace) {
// 			$str = preg_replace($pattern, $replace, $str);
// 		}
		
// 		return trim($str);
// 	}
// }