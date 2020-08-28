<?php

namespace Depictr;

use Closure;
use Depictr\Contracts\Browser;
use Illuminate\Http\Request;
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
        if ($this->shouldDepict($request)) {
            try {
                $response = $this->browser->render($request->fullUrl());
            } catch (Throwable $exception) {
                return $next($request);
            }

            return response(
                $response,
                200,
                ['X-Depicted' => now()->toString()]
            );
        }

        return $next($request);
    }

    /**
     * Returns whether or not the request is made by a search
     * engine crawler.
     *
     * @param Request  $request
     *
     * @return bool
     */
    private function shouldDepict(Request $request): bool
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

        return app()->environment(config('depictr.environments', []))
            && $this->comesFromCrawler($request);
    }

    /**
     * Returns whether not the request is made by a valid crawler.
     *
     * @param Request  $request
     *
     * @return bool
     */
    private function comesFromCrawler(Request $request): bool
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
     * The method returns whether the request is an excluded URL
     * or not. \Illuminate\Http\Request::is(...$patterns)
     * is used, which allows you to match routes
     * using wildcards.
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
}
