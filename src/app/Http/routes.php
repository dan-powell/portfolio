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
Route::group(['prefix' => config('portfolio.routes.api.prefix')], function()
{

    // Projects
    Route::resource(
        config('portfolio.routes.api.project'),
        'DanPowell\Portfolio\Http\Controllers\Api\ProjectController',
        ['except' => ['create', 'edit']]
    );

    // Project Sections
    Route::resource(
        config('portfolio.routes.api.project') . '.' . config('portfolio.routes.api.section'),
        'DanPowell\Portfolio\Http\Controllers\Api\ProjectSectionController',
        ['except' => ['create', 'edit', 'update', 'destroy']]
    );

    // Sections
    Route::resource(
        config('portfolio.routes.api.section'),
        'DanPowell\Portfolio\Http\Controllers\Api\SectionController',
        ['except' => ['create', 'edit']]
    );

    // Tags
    Route::get(config('portfolio.routes.api.tagSearch'), ['as' => 'api.tag.search', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\TagController@search']);
    Route::resource(
        config('portfolio.routes.api.tag'),
        'DanPowell\Portfolio\Http\Controllers\Api\TagController',
        ['except' => ['create', 'edit']]
    );

    // Assets
    //Route::resource('assets', 'DanPowell\Portfolio\Http\Controllers\Api\AssetController', ['except' => ['create', 'edit']]);
	Route::get('assets', ['as' => 'assets.index', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\AssetController@index']);
	Route::post('assets', ['as' => 'assets.create', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\AssetController@store']);
	Route::put('assets', ['as' => 'assets.update', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\AssetController@update']);
	Route::delete('assets', ['as' => 'assets.delete', 'uses' => 'DanPowell\Portfolio\Http\Controllers\Api\AssetController@destroy']);

    // Project Pages
    Route::resource(
        config('portfolio.routes.api.project') . '.' . config('portfolio.routes.api.page'),
        'DanPowell\Portfolio\Http\Controllers\Api\ProjectPageController',
        ['except' => ['create', 'edit', 'update', 'destroy']]
    );

    // Pages
    Route::resource(
        config('portfolio.routes.api.page'),
        'DanPowell\Portfolio\Http\Controllers\Api\PageController',
        ['except' => ['create', 'post', 'edit']]
    );

    // Project Sections
    Route::resource(
        config('portfolio.routes.api.page') . '.' . config('portfolio.routes.api.section'),
        'DanPowell\Portfolio\Http\Controllers\Api\PageSectionController',
        ['except' => ['create', 'edit', 'update', 'destroy']]
    );

});