<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language', 'en');

        if (! in_array($locale, ['en',  'ka', 'az', 'ru'], true)) {
            $locale = 'en';
        }

        app()->setlocale($locale);

        return $next($request);
    }
}
