<?php

namespace Depictr\Tests;

use Depictr\Middleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\App;

/**
 * @internal
 * @coversNothing
 */
class ServiceProviderTest extends TestCase
{
    /** @test */
    public function the_middleware_is_registered(): void
    {
        $kernel = App::make(Kernel::class);

        $this->assertTrue($kernel->hasMiddleware(Middleware::class));
    }
}
