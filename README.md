# Depictr
## Is this useful to you?
**Consider [sponsoring me on github](https://github.com/sponsors/juhlinus)!**

## Installation
`composer require juhlinus/depictr`

## Usage
Require the package from packagist, and define the middleware like so:

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Depictr\Middleware::class, // Add the Depictr Middleware
    ];
    [...]
```

Optionally if you wish to change the allowed crawlers you can publish the config file.

```
php artisan vendor:publish --provider="Depictr\ServiceProvider"
```
