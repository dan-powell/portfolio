<?php namespace DanPowell\Portfolio;

use DanPowell\Portfolio\Console\Commands\Seed;
use DanPowell\Portfolio\Console\Commands\Migrate;

class PortfolioServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('DanPowell\Portfolio\Providers\ViewComposerServiceProvider');

        // Include package routes
        include __DIR__.'/Http/routes.php';

        // Tell Laravel where to load the views from
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'portfolio');


        $this->app->bindShared('command.portfolio.seed', function ($app) {
            return new Seed();
        });

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {

        $this->commands('command.portfolio.seed');

        // Publish Views
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/portfolio'),
        ], 'views');

        // Publish Config
        $this->publishes([
            __DIR__.'/../config/portfolio.php' => config_path('portfolio.php'),
        ], 'config');

        // Publish Migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => $this->app->databasePath().'/migrations',
        ], 'migrations');

    }



}