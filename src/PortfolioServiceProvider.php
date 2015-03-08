<?php namespace DanPowell\Portfolio;

class PortfolioServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('DanPowell\Portfolio\App\Providers\ViewComposerServiceProvider');

        // Include package routes
        include __DIR__.'/app/Http/routes.php';

        // Tell Laravel where to load the views from
        $this->loadViewsFrom(__DIR__.'/resources/views', 'portfolio');

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {

        // Publish Views
        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/vendor/portfolio'),
        ], 'views');

        // Publish Config
        $this->publishes([
            __DIR__.'/config/portfolio.php' => config_path('portfolio.php'),
        ], 'config');

        // Publish Migrations
        $this->publishes([
            __DIR__.'/database/migrations' => $this->app->databasePath().'/migrations',
        ], 'migrations');

    }

}