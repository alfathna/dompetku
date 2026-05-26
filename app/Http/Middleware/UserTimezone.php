<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->timezone) {
            $tz = auth()->user()->timezone;
            
            // Map old values if there are any lingering in the database
            $map = [
                'WIB' => 'Asia/Jakarta',
                'WITA' => 'Asia/Makassar',
                'WIT' => 'Asia/Jayapura',
                'GMT+07:00 (Jakarta)' => 'Asia/Jakarta',
                'GMT+08:00 (Makassar)' => 'Asia/Makassar',
                'GMT+09:00 (Jayapura)' => 'Asia/Jayapura',
                'GMT+00:00 (London)' => 'UTC',
            ];
            
            if (isset($map[$tz])) {
                $tz = $map[$tz];
            }
            
            config(['app.timezone' => $tz]);
            date_default_timezone_set($tz);
        }

        return $next($request);
    }
}
