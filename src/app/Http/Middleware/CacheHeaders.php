<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheHeaders
{
    public function __construct(protected int $maxAge = 60)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->hasSession() || $request->user()) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'public, max-age='.$this->maxAge);

        return $response;
    }
}
