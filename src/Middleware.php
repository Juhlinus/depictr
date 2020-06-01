<?php

namespace Depictr;

use Closure;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\Client as PantherClient;

class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
                $response['code'],
            );
        }

        return $next($request);
    }

    /**
     * Returns whether or not the request is made by a search
     * engine crawler.
     *
     * @param      \Illuminate\Http\Request  $request  The request
     *
     * @return     boolean
     */
    private function shouldDepict(Request $request): bool
    {
        return app()->environment(config('depictr.environments', []))
            && $this->comesFromCrawler($request)
            && $request->isMethod('GET')
            && ! $request->header('X-Inertia')
            && ! $this->whiteListed($request);
    }

    /**
     * Returns whether not the request is made by a valid crawler.
     *
     * @param      \Illuminate\Http\Request  $request  The request
     *
     * @return     boolean
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
     * Renders a HTML page for the search enginie crawler.
     *
     * @param      string  $url    The url
     *
     * @return     array   Status code and raw HTML.
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
     * Returns whether not the request is a whitelisted URL. Uses
     * $request->is() so `*` as wildcard is permitted.
     *
     * @param      \Illuminate\Http\Request  $request  The request
     *
     * @return     boolean
     */
    private function whiteListed(Request $request): bool
    {
        return $request->is(config('depictr.whitelist', []));
    }
}
