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

Route::get(config('portfolio.routes.public.index'), array('as' => 'projects.index', 'uses' => 'DanPowell\Portfolio\Http\Controllers\ProjectController@index'));

Route::get(config('portfolio.routes.public.show'), ['as' => 'projects.show', 'uses' => 'DanPowell\Portfolio\Http\Controllers\ProjectController@show']);

Route::get(config('portfolio.routes.public.showPage'), ['as' => 'projects.page', 'uses' => 'DanPowell\Portfolio\Http\Controllers\ProjectController@page']);


// Admin area
Route::group(['prefix' => config('portfolio.routes.admin.prefix'), 'middleware' => ['auth']], function()
{

    Route::get(config('portfolio.routes.admin.home'), ['as' => 'admin', function() {
        return view('portfolio::admin.base');
    }]);

});


// RESTful API
Route::group(['prefix' => 'api'], function()
{

    // Admin project items
    Route::resource('project', 'DanPowell\Portfolio\Http\Controllers\Api\ProjectController', ['except' => ['create', 'edit']]);

    // Admin project section items
    Route::resource('project.section', 'DanPowell\Portfolio\Http\Controllers\Api\ProjectSectionController', ['except' => ['create', 'edit']]);

    // Admin section items
    Route::resource('section', 'DanPowell\Portfolio\Http\Controllers\Api\SectionController', ['except' => ['create', 'edit']]);

    // Admin tag items
    Route::get('tag/search', ['as' => 'tag.search', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\TagController@search']);
    Route::resource('tag', 'DanPowell\Portfolio\Http\Controllers\Api\TagController', ['except' => ['create', 'edit']]);


    // Admin project assets
    //Route::resource('assets', 'DanPowell\Portfolio\Http\Controllers\Api\AssetController', ['except' => ['create', 'edit']]);

    	Route::get('assets', ['as' => 'assets.index', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\AssetController@index']);
    	Route::post('assets', ['as' => 'assets.create', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\AssetController@store']);
    	Route::put('assets', ['as' => 'assets.update', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\AssetController@update']);
    	Route::delete('assets', ['as' => 'assets.delete', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\AssetController@destroy']);


    // Admin project page items
    Route::resource('project.page', 'DanPowell\Portfolio\Http\Controllers\Api\ProjectPageController', ['except' => ['create', 'edit']]);

    // Admin page items
    Route::resource('page', 'DanPowell\Portfolio\Http\Controllers\Api\PageController', ['except' => ['create', 'edit']]);

    // Admin project section items
    Route::resource('page.section', 'DanPowell\Portfolio\Http\Controllers\Api\PageSectionController', ['except' => ['create', 'edit']]);


});