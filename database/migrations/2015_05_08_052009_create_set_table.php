<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sets', function(Blueprint $table){
			$table->primary('code');
			$table->string('code')->unique();
			$table->string('name');
			$table->text('description')->nullable();
			$table->string('block')->nullable();
			$table->string('border')->nullable();
			$table->string('type')->nullable();
			$table->integer("common")->default(0);
    		$table->integer("uncommon")->default(0);
    		$table->integer("rare")->default(0);
    		$table->integer("mythicRare")->default(0);
    		$table->integer("basicLand")->default(0);
    		$table->integer("total")->default(0);
			$table->date('released')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sets');
	}


}
