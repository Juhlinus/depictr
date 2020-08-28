<?php

namespace Depictr\Tests\__fixtures;

use Depictr\Contracts\Browser;
use Exception;
use Illuminate\Support\Str;
use Throwable;

class FakeBrowser implements Browser
{
    /**
     * Renders a HTML page.
     *
     * @param  string  $url
     * @return string
     * @throws Throwable
     */
    public function render(string $url): string
    {
        if (Str::endsWith($url, '/fail')) {
            throw new Exception('Something went wrong');
        }

        return '<html><head></head><body>Fake Depictr Response</body></html>';
    }
}
