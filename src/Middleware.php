<?php

namespace Depictr;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\Client as PantherClient;

class Middleware
{
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
                $response = $this->requestRenderedPage($request->fullUrl());
            } catch (RuntimeException $exception) {
                return $next($request);
            }

            return response(
                $response['content'],
                $response['code']
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
        return app()->environment(config('depictr.environments', []))
            && $this->comesFromCrawler($request)
            && $request->isMethod('GET')
            && ! $request->header('X-Inertia')
            && $this->UrlIsNotExcluded($request);
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
        return ! empty($request->userAgent())
            && Str::contains(
                strtolower($request->userAgent()),
                config('depictr.crawlers')
            );
    }

    /**
     * Renders a HTML page for the search engine crawler.
     *
     * @param string  $url
     *
     * @return array
     */
    private function requestRenderedPage(string $url): array
    {
        $client = PantherClient::createChromeClient();
        $client->request('GET', $url);

        $pageSource = $client->getPageSource();

        $client->close();

        return [
            'content' => $pageSource,
            'code' => 200,
        ];
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
    private function UrlIsNotExcluded(Request $request): bool
    {
        return $request->is(
            config('depictr.whitelist', [])
        );
    }
}
