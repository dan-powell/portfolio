<?php namespace DanPowell\Portfolio\App\Providers;

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
        $this->app->view->composer('portfolio::partials.list','DanPowell\Portfolio\App\Http\ViewComposers\ProjectListComposer');
    }

}