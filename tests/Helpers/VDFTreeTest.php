<?php

require __DIR__ . '/../TestCase.php';

use X10WeaponStatsApi\Helpers\VDFTree\VDFTree;
use X10WeaponStatsApi\Models;

class VDFTreeTest extends TestCase {
	
	public function testWriteTreeToDatabase() {
		$file = __DIR__ . '/vdf_files/example_tf2items.weapons.txt';
		 
		$tree = new VDFTree($file);
		 
		$config = Models\Config::where('name', '=', 'master')->get()->first();
		 
		$tree->writeToDatabase($config);
		
		$this->assertEquals(true, true);
	}
	
}