<?php

namespace Depictr\Browsers;

use Depictr\Contracts\Browser;
use Symfony\Component\Panther\Client as PantherClient;
use Throwable;

class ChromeBrowser implements Browser
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
        $client = PantherClient::createChromeClient();
        $client->request('GET', $url);

        return tap($client->getPageSource(), function () use ($client) {
            $client->close();
        });
    }
}
