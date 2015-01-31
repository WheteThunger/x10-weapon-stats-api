<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialSchema extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('configs', function(Blueprint $table) {
	       $table->increments('id');
	       $table->string('name');
	       $table->integer('parent_id');
	       $table->timestamps();
	    });
	    
		Schema::create('people', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('tf2items_id')->unique();
			$table->timestamps();
		});
		
		Schema::create('weapons', function(Blueprint $table) {
		    $table->integer('defindex');
		    $table->primary('defindex');
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
		
		Schema::create('attributes', function(Blueprint $table) {
			$table->integer('defindex');
			$table->primary('defindex');
			$table->string('name');
			$table->string('attribute_class');
			$table->string('description_string');
			$table->string('description_format');
			$table->string('effect_type');
			$table->boolean('hidden');
			$table->boolean('stored_as_integer');
			$table->timestamps();
		});
		
		Schema::create('classes', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
		});
		
		Schema::create('class_weapon', function(Blueprint $table) {
			$table->integer('class_id');
			$table->integer('weapon_defindex');
			$table->primary(['class_id', 'weapon_defindex']);
		});
		
		Schema::create('weapon_instance', function(Blueprint $table) {
			$table->integer('config_id');
			$table->integer('person_id');
			$table->integer('weapon_defindex');
			$table->integer('attribute_defindex');
			$table->primary([
			    'config_id', 
			    'person_id', 
			    'weapon_defindex', 
			    'attribute_defindex'
			]);
			
			$table->string('attribute_value');
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
	    Schema::dropIfExists('configs');
		Schema::dropIfExists('people');
		Schema::dropIfExists('weapons');
		Schema::dropIfExists('attributes');
		Schema::dropIfExists('classes');
		Schema::dropIfExists('class_weapon');
		Schema::dropIfExists('weapon_instance');
	}

}