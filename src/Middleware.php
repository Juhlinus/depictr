<?php

namespace Depictr;

use Closure;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Panther\Client as PantherClient;

class Middleware
{
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

    private function shouldDepict(Request $request): bool
    {
        return (app()->environment('production')
            || app()->environment('testing'))
            && $this->comesFromCrawler($request)
            && $request->isMethod('GET')
            && ! $request->header('X-Inertia');
    }

    private function comesFromCrawler(Request $request): bool
    {
        return ! empty($request->userAgent())
            && Str::contains(
                strtolower($request->userAgent()),
                config('depictr.crawlers')
            );
    }

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
}
