<?php namespace DanPowell\Portfolio\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->view->composer('portfolio::partials.list','DanPowell\Portfolio\Http\ViewComposers\ProjectListComposer');
    }

}