<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('editions', function(Blueprint $table){
			$table->primary('multiverse_id');
			$table->integer('multiverse_id')->unique();
			$table->string('rarity');
			$table->string('artist');
			$table->text('flavor');
			$table->integer('number');
			$table->string('layout');
			$table->dateTime('released')->nullable();
			$table->string('code');
			$table->integer('card_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('editions');
	}

}
