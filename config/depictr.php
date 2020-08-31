<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Depictr Debug Mode
    |--------------------------------------------------------------------------
    |
    | When Depictr is in debug mode, all included endpoints are rendered
    | as-if the request is coming from a crawler. When disabled, this
    | only happens for the crawlers you've defined.
    |
    */

    'debug' => env('DEPICTR_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Crawlers
    |--------------------------------------------------------------------------
    |
    | An engine with all the allowed crawlers. This list can be extended and
    | reduced freely. This list will be traversed when checking if a page
    | should be returned as static html or not.
    |
    */

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

    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded
    |--------------------------------------------------------------------------
    |
    | URLs that should NOT be processed by Depictr. This is useful for plain
    | files such as sitemap.txt where Depictr will wrap it in a stripped
    | down HTML file. Uses $request->is(), so using `*` for wildcard
    | is permitted. The admin route and its sub-routes have
    | been added to showcase the functionality.
    |
    */

    'excluded' => [
        'admin/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Environments
    |--------------------------------------------------------------------------
    |
    | The application environments on which Depictr should be enabled.
    |
    */

    'environments' => [
        'production',
        'testing',
    ],

];
