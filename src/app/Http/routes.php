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

Route::get('/portfolio', array('as' => 'projects.index', 'uses' => 'DanPowell\Portfolio\Http\Controllers\ProjectsController@index'));

Route::get('/portfolio/{slug}', ['as' => 'projects.show', 'uses' => 'DanPowell\Portfolio\Http\Controllers\ProjectsController@show']);

Route::get('/portfolio/{slug}/{pageSlug}', ['as' => 'projects.page', 'uses' => 'DanPowell\Portfolio\Http\Controllers\ProjectsController@page']);


// Admin area
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function()
{

    Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Admin\AdminController@index']);

    // RESTful API
    Route::group(['prefix' => 'api'], function()
    {

        // Admin project items
        Route::resource('project', 'DanPowell\Portfolio\Http\Controllers\Api\ProjectController', ['except' => ['show', 'create']]);

    });

});