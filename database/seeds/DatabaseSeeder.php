<?php

require 'ClassTableSeeder.php';
require 'PersonTableSeeder.php';
require 'ConfigTableSeeder.php';

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//Model::unguard();

		$this->call('ClassTableSeeder');
		$this->call('PersonTableSeeder');
		$this->call('ConfigTableSeeder');
	}

}
