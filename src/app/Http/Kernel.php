<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'cache.headers' => \App\Http\Middleware\CacheHeaders::class,
    ];
}
