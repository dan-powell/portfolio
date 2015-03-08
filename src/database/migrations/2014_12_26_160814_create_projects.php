<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjects extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function($table)
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
            $table->string('url', 255);
            $table->tinyInteger('featured')->default(0);
            $table->string('template', 80);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
