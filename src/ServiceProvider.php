<?php

namespace Depictr;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->registerMiddleware();
        $this->publishConfig();
    }

    protected function registerMiddleware()
    {
        $this->app[Kernel::class]->pushMiddleware(Middleware::class);
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/depictr.php' => config_path('depictr.php'),
        ], 'depictr-config');
    }
}
