<?php

namespace Depictr\Tests;

use Depictr\Contracts\Browser;
use Depictr\ServiceProvider;
use Depictr\Tests\__fixtures\FakeBrowser;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->bind(Browser::class, FakeBrowser::class);

        View::addLocation(__DIR__.'/views');
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
