<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class InitialSchema extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('people', function($table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('tf2items_id')->unique();
			$table->timestamps();
		});
		
		Schema::create('weapons', function($table) {
			$table->increments('id');
			$table->integer('defindex')->unique();
			$table->string('item_class');
			$table->string('item_type_name');
			$table->string('item_name');
			$table->string('item_description');
			$table->boolean('proper_name');
			$table->string('item_slot');
			$table->integer('item_quality');
			$table->string('image_url');
			$table->string('image_url_large');
			$table->integer('min_ilevel');
			$table->integer('max_ilevel');
			$table->timestamps();
		});
		
		Schema::create('attributes', function($table) {
			$table->integer('defindex');
			$table->primary('defindex');
			// Or should we just call this attribute_id
			$table->string('name');
			$table->string('attribute_class');
			$table->string('description_string');
			$table->string('description_format');
			$table->string('effect_type');
			$table->boolean('hidden');
			$table->boolean('stored_as_integer');
			$table->timestamps();
		});
		
		Schema::create('classes', function($table) {
			$table->increments('id');
			$table->string('name');
		});
		
		Schema::create('class_weapon', function($table) {
			$table->integer('class_id');
			$table->integer('weapon_defindex');
			$table->primary(['class_id', 'weapon_defindex']);
		});
		
		Schema::create('attribute_weapon', function($table) {
			$table->integer('attribute_id');
			$table->integer('weapon_id');
			$table->primary(['attribute_id', 'weapon_id']);
			$table->timestamps();
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('people');
		Schema::drop('weapons');
		Schema::drop('attributes');
		Schema::drop('classes');
		Schema::drop('class_weapon');
		Schema::drop('attribute_weapon');
	}

}
