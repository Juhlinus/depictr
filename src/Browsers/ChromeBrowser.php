<?php

namespace Depictr\Browsers;

use Depictr\Concerns\ProvidesBrowser;
use Depictr\Contracts\Browser;
use Depictr\OperatingSystem;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Throwable;

class ChromeBrowser implements Browser
{
    use ProvidesBrowser;

    protected function driverPath(): string
    {
        if (OperatingSystem::onWindows()) {
            return realpath(__DIR__.'/../../../bin/chromedriver-win.exe');
        }

        if (OperatingSystem::onMac()) {
            return realpath(__DIR__.'/../../../bin/chromedriver-mac');
        }

        return realpath(__DIR__.'/../../../bin/chromedriver-linux');
    }

    protected function remoteWebDriver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        )
        );
    }

    protected function environment(): array
    {
        if (OperatingSystem::onWindows() || OperatingSystem::onMac()) {
            return [];
        }

        return ['DISPLAY' => $_ENV['DISPLAY'] ?? ':0'];
    }

    protected function processArguments(): array
    {
        return [];
    }

    /**
     * Renders a HTML page.
     *
     * @param  string  $url
     * @return string
     * @throws Throwable
     */
    public function render(string $url): string
    {
        return $this->browse(function ($driver) use ($url) {
            $driver->navigate()->to($url);

            return $driver->getPageSource();
        });
    }
}
