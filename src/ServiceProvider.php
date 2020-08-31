<?php

namespace Depictr;

use Depictr\Browsers\ChromeBrowser;
use Depictr\Contracts\Browser;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware();
        $this->publishConfig();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Browser::class, ChromeBrowser::class);
    }

    /**
     * Register the Depictr middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $this->app[Kernel::class]->pushMiddleware(Middleware::class);
    }

    /**
     * Publishes the Depictr config.
     *
     * @return void
     */
    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/depictr.php' => config_path('depictr.php'),
        ], 'depictr-config');
    }
}
