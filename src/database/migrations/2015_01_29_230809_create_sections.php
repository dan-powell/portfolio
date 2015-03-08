<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSections extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('sections', function($table)
		{
    		$table->increments('id');
            $table->text('markup');
            $table->text('scripts');
            $table->text('styles');
            $table->string('section_classes', 255);
            $table->string('container_classes', 255);
            $table->integer('rank');
            $table->integer('attachment_id');
            $table->string('attachment_type', 255);
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sections');
	}

}