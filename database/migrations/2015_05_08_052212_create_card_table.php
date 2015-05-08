<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cards', function(Blueprint $table){
			$table->increments('id');
			$table->string('name');
			$table->integer('cms');
			$table->string('cost');
			$table->text('text');
			$table->integer('power');
			$table->integer('toughness');
			$table->integer('loyalty');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cards');
	}

}
