# 📽 Depictr
## 💰 Is this useful to you?
**Consider [sponsoring me on github](https://github.com/sponsors/juhlinus)! 🙏**

## 💾 Installation
```
composer require juhlinus/depictr
```

## 📝 Config

You can publish the config by running the `artisan vendor:publish` command like so:

```
php artisan vendor:publish --provider="Depictr\ServiceProvider"
```

### 🕷 Crawlers

The following crawlers are defined out of the box:

```php
return [
    'crawlers' => [
        /*
        |--------------------------------------------------------------------------
        | Search engines
        |--------------------------------------------------------------------------
        |
        | These are the list of all the regular search engines that crawl your
        | website on a regular basis and is the crucial if you want good
        | SEO.
        |
        */
        'googlebot',            // Google
        'duckduckbot',          // DuckDuckGo
        'bingbot',              // Bing
        'yahoo',                // Yahoo
        'yandexbot',            // Yandex

        /*
        |--------------------------------------------------------------------------
        | Social networks
        |--------------------------------------------------------------------------
        |
        | Allowing social networks to crawl your website will help the social
        | networks to create "social-cards" which is what people see when
        | they link to your website on the social network websites.
        |
        */
        'facebookexternalhit',  // Facebook
        'twitterbot',           // Twitter
        'whatsapp',             // WhatsApp
        'linkedinbot',          // LinkedIn
        'slackbot',             // Slack

        /*
        |--------------------------------------------------------------------------
        | Other
        |--------------------------------------------------------------------------
        |
        | For posterity's sake you want to make sure that your website can be
        | crawled by Alexa. This will archive your website so that future
        | generations may gaze upon your craftsmanship.
        |
        */
        'ia_archiver',          // Alexa
    ]
]        
```

### ⛔ Exclusion

Depictr comes with the option of excluding an array of urls that shouldn't be processed.

This is useful for plain text files like `sitemap.txt`, where Panther will wrap it in a stripped down HTML file. Use of wildcard is permitted.

Per default the `admin` route and its sub-routes are excluded in the config file.

### 🏞 Environments

You can specify which environments Depictr should run on. Default is `testing` and `production`.

## Installation Chrome

If already have Chrome installed, can skip this step.

Installation attention, the major version of Chromium (Chrome) browser must be the same as the Chrome driver.

### Ubuntu

```
sudo apt-get update
sudo apt-get upgrade
sudo apt-get install chromium-browser
chromium-browser -version
```

Install the old version for ChromeDriver, the same major version as Chromium (https://chromedriver.storage.googleapis.com/index.html):

```
curl -O https://chromedriver.storage.googleapis.com/84.0.4147.30/chromedriver_linux64.zip
unzip chromedriver_linux64.zip
```

Finlly, add ChromeDriver path to `.env`:

```
PANTHER_CHROME_DRIVER_BINARY=/path/to/chromedriver
```

### Heroku

Add Buildpacks (MUST follow this order):

1. https://github.com/heroku/heroku-buildpack-chromedriver
2. https://github.com/heroku/heroku-buildpack-google-chrome
3. heroku/php

Finlly, add key `PANTHER_CHROME_DRIVER_BINARY` and value `/app/.chromedriver/bin/chromedriver` pair to Config Vars.