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