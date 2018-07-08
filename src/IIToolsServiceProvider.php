<?php

namespace Immersioninteractive\ToolsController;

use Illuminate\Support\ServiceProvider;

class IIToolsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IITools::class, function () {
            return new IITools();
        });
        $this->app->alias(IITools::class, 'IITools');
    }
}
