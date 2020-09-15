<?php

namespace Depictr;

use Closure;
use Depictr\Contracts\Browser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Middleware
{
    /**
     * @var Browser
     */
    protected $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure  $next
     *
     * @return Response
     */
    public function handle($request, Closure $next): Response
    {
//        if (! $this->shouldDepict($request)) {
//            return $next($request);
//        }

//        try {
            $contents = $this->browser->render($request->fullUrl());
//        } catch (Throwable $exception) {
//            Log::error($exception);
//            return $next($request);
//        }

        return new Response(
            $contents,
            200,
            ['X-Depicted' => now()->toString()]
        );
    }

    /**
     * Returns whether or not the request is made by a search
     * engine crawler.
     *
     * @param Request  $request
     *
     * @return bool
     */
    protected function shouldDepict(Request $request): bool
    {
        if (! $request->isMethod('GET')
            || $request->header('X-Inertia')
            || $this->isExcluded($request)
        ) {
            return false;
        }

        if (config('depictr.debug')) {
            return true;
        }

        return $this->environmentEnabled()
            && $this->isFromCrawler($request);
    }

    /**
     * Determine whether not the request is made by a valid crawler.
     *
     * @param Request  $request
     *
     * @return bool
     */
    protected function isFromCrawler(Request $request): bool
    {
        if (empty($userAgent = $request->userAgent())) {
            return false;
        }

        return collect(config('depictr.crawlers'))
            ->map(function ($crawler) {
                return strtolower($crawler);
            })
            ->contains(strtolower($userAgent));
    }

    /**
     * Determine whether the Request is for an excluded page.
     *
     * @param Request  $request
     *
     * @return bool
     */
    private function isExcluded(Request $request): bool
    {
        return $request->is(
            ...config('depictr.excluded', [])
        );
    }

    /**
     * Determine whether Depictr is enabled
     * for this environment.
     *
     * @return bool
     */
    protected function environmentEnabled(): bool
    {
        return app()->environment(
            config('depictr.environments', [])
        );
    }
}
