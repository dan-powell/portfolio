<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/portfolio', array('as' => 'projects.index', 'uses' => 'DanPowell\Portfolio\App\Http\Controllers\ProjectsController@index'));

Route::get('/portfolio/{slug}', ['as' => 'projects.show', 'uses' => 'DanPowell\Portfolio\App\Http\Controllers\ProjectsController@show']);

Route::get('/portfolio/{slug}/{pageSlug}', ['as' => 'projects.page', 'uses' => 'DanPowell\Portfolio\App\Http\Controllers\ProjectsController@page']);


