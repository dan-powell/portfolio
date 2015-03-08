<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTag extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    	Schema::create('tags', function($table)
		{
    		$table->increments('id');
            $table->timestamps();
            $table->string('title', 255);
		});
		
		Schema::create('taggables', function($table)
		{
    		$table->integer('tag_id');
            $table->integer('taggable_id');
            $table->string('taggable_type', 255);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tags');
		
		Schema::drop('taggables');
	}

}
