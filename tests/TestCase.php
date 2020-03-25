<?php

namespace Depictr\Tests;

use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__.'/views');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Depictr\ServiceProvider::class,
        ];
    }
}
