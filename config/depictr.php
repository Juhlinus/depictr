<?php

return [
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
    | Environments
    |--------------------------------------------------------------------------
    |
    | Which Laravel environments should depictr be active for.
    |
    */
    'environments' => [
        'production',
        'testing',
    ],
];
