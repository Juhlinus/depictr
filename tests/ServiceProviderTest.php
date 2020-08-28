<?php

namespace Depictr\Tests;

use Depictr\Middleware;
use Inertia\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Response;

/**
 * @internal
 * @coversNothing
 */
class ServiceProviderTest extends TestCase
{
    /** @test */
    public function the_middleware_is_registered()
    {
        $kernel = App::make(Kernel::class);

        $this->assertTrue($kernel->hasMiddleware(Middleware::class));
    }

    /** @test */
    public function it_renders_a_html_page()
    {
        $this->app['config']['depictr.crawlers'] = [
            'symfony'
        ];
        $this->app['config']['depictr.environments'] = [
            'testing'
        ];

        $request = Request::create('/', 'GET');
        $middleware = new \Depictr\Middleware();
        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals(200, $response->getStatusCode());
    }
}
