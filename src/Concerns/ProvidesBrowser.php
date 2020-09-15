<?php

namespace Depictr\Concerns;

use Closure;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Symfony\Component\Process\Process;

trait ProvidesBrowser
{
    /** @var Process|null */
    protected $browser;

    /**
     * @var RemoteWebDriver|null
     */
    protected $driver;

    /**
     * Path to the Web Driver binary.
     *
     * @return string
     */
    abstract protected function driverPath(): string;

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    abstract protected function remoteWebDriver(): RemoteWebDriver;

    /**
     * The Web Driver environment variables.
     *
     * @return array
     */
    abstract protected function environment(): array;

    /**
     * Extra Web Driver Command-line arguments.
     *
     * @return array
     */
    abstract protected function processArguments(): array;

    /**
     * Spawns a new Browser (web driver) process.
     *
     * @return Process
     */
    protected function createWebDriverProcess(): Process
    {
        $driverPath = realpath($this->driverPath());

        return new Process(
            array_merge([$driverPath], $this->processArguments()),
            null,
            $this->environment()
        );
    }

    public function browse(Closure $callback)
    {
        $browser = $this->createWebDriverProcess();
        $browser->start();
        $driver = $this->remoteWebDriver();

        $response = $callback($driver);

        $driver->quit();
        $browser->stop();

        return $response;
    }
}
