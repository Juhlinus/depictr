<?php

namespace Depictr\Contracts;

use Throwable;

interface Browser
{
    /**
     * Renders a HTML page.
     *
     * @param  string  $url
     * @return string
     * @throws Throwable
     */
    public function render(string $url): string;
}
