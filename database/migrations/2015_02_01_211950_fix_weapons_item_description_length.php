<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixWeaponsItemDescriptionLength extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('weapons', function(Blueprint $table) {
		    $table->string('item_description', 500)->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('weapons', function(Blueprint $table) {
		    $table->string('item_description')->change();
		});
	}

}
