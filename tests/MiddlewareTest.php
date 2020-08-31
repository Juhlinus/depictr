<?php

namespace Depictr\Tests;

use Depictr\Middleware;
use Illuminate\Support\Facades\Route;

class MiddlewareTest extends TestCase
{
    /** @test */
    public function it_renders_the_html(): void
    {
        config([
            'depictr.crawlers' => [request()->userAgent()],
            'depictr.environments' => [config('app.env'), 'local'],
            'depictr.excluded' => ['test'],
        ]);

        Route::middleware(Middleware::class)->get('/', function () {
            return view('app');
        });

        $response = $this->get('/');

        $response->assertHeader('X-Depicted');
        $response->assertOk();
        $this->assertEquals(
            '<html><head></head><body>Fake Depictr Response</body></html>',
            $response->getContent()
        );
    }

    /** @test */
    public function it_lets_blade_handle_the_request_when_something_goes_wrong(): void
    {
        config([
            'depictr.crawlers' => [request()->userAgent()],
            'depictr.environments' => [config('app.env'), 'local'],
            'depictr.excluded' => ['test'],
        ]);

        Route::middleware(Middleware::class)->get('/fail', function () {
            return view('app');
        });

        $response = $this->get('/fail');

        $response->assertHeaderMissing('X-Depicted');
        $response->assertOk();
        $this->assertEquals(
            view('app')->render(),
            $response->getContent(),
        );
    }

    /** @test */
    public function it_does_not_run_when_the_environment_does_not_match(): void
    {
        config([
            'depictr.crawlers' => [request()->userAgent()],
            'depictr.environments' => ['production', 'local'],
            'depictr.excluded' => ['test'],
        ]);

        Route::middleware(Middleware::class)->get('/', function () {
            return view('app');
        });

        $response = $this->get('/');

        $response->assertHeaderMissing('X-Depicted');
        $response->assertOk();
        $this->assertEquals(
            view('app')->render(),
            $response->getContent(),
        );
    }

    /** @test */
    public function it_does_not_run_when_the_page_is_excluded(): void
    {
        config([
            'depictr.crawlers' => [request()->userAgent()],
            'depictr.environments' => [config('app.env'), 'local'],
            'depictr.excluded' => ['test'],
        ]);

        Route::middleware(Middleware::class)->get('/test', function () {
            return view('app');
        });

        $response = $this->get('/test');

        $response->assertHeaderMissing('X-Depicted');
        $response->assertOk();
        $this->assertEquals(
            view('app')->render(),
            $response->getContent(),
        );
    }

    /** @test */
    public function it_does_not_run_if_the_client_is_not_a_registered_crawler(): void
    {
        config([
            'depictr.crawlers' => ['Google'],
            'depictr.environments' => [config('app.env'), 'local'],
            'depictr.excluded' => ['test'],
        ]);

        Route::middleware(Middleware::class)->get('/', function () {
            return view('app');
        });

        $response = $this->get('/');

        $response->assertHeaderMissing('X-Depicted');
        $response->assertOk();
        $this->assertEquals(
            view('app')->render(),
            $response->getContent(),
        );
    }

    /** @test */
    public function it_does_not_run_if_the_request_is_not_a_get_request(): void
    {
        config([
            'depictr.crawlers' => [request()->userAgent()],
            'depictr.environments' => [config('app.env'), 'local'],
            'depictr.excluded' => ['test'],
        ]);

        Route::middleware(Middleware::class)->post('/', function () {
            return view('app');
        });

        $response = $this->post('/');

        $response->assertHeaderMissing('X-Depicted');
        $response->assertOk();
        $this->assertEquals(
            view('app')->render(),
            $response->getContent(),
        );
    }

    /** @test */
    public function it_does_not_run_when_the_request_is_an_inertia_js_partial_call(): void
    {
        config([
            'depictr.crawlers' => [request()->userAgent()],
            'depictr.environments' => [config('app.env'), 'local'],
            'depictr.excluded' => ['test'],
        ]);

        Route::middleware(Middleware::class)->get('/', function () {
            return view('app');
        });

        $response = $this->get('/', ['X-Inertia' => true]);

        $response->assertHeaderMissing('X-Depicted');
        $response->assertOk();
        $this->assertEquals(
            view('app')->render(),
            $response->getContent(),
        );
    }

    /** @test */
    public function it_considers_all_requests_as_coming_from_a_crawler_on_all_environments_when_debug_mode_is_enabled(): void
    {
        config([
            'depictr.debug' => true,
            'depictr.crawlers' => ['Google'],
            'depictr.environments' => ['production', 'local'],
            'depictr.excluded' => ['test'],
        ]);

        Route::middleware(Middleware::class)->get('/', function () {
            return view('app');
        });

        $response = $this->get('/');

        $response->assertHeader('X-Depicted');
        $response->assertOk();
        $this->assertEquals(
            '<html><head></head><body>Fake Depictr Response</body></html>',
            $response->getContent()
        );
    }

    /** @test */
    public function it_ignores_debug_mode_when_the_request_has_the_x_depictr_header_to_prevent_infinite_rendering_loops(): void
    {
        config([
            'depictr.debug' => true,
            'depictr.crawlers' => ['Google'],
            'depictr.environments' => ['production', 'local'],
            'depictr.excluded' => ['test'],
        ]);

        Route::middleware(Middleware::class)->get('/', function () {
            return view('app');
        });

        $response = $this->get('/', ['X-Depictr' => true]);

        $response->assertHeaderMissing('X-Depicted');
        $response->assertOk();
        $this->assertEquals(
            view('app')->render(),
            $response->getContent(),
        );
    }
}
