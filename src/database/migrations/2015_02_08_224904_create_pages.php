<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('pages', function($table)
		{
    		$table->increments('id');
    		$table->timestamps();
            $table->string('title', 255);
            $table->string('slug', 80);
            $table->string('seo_title', 255);
            $table->string('seo_description', 255);
            $table->text('markup');
            $table->text('scripts');
            $table->text('styles');
            $table->string('template', 80);
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
		Schema::drop('pages');
	}

}