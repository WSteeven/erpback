<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyArtisanKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $headerKey = $request->header('X-ARTISAN-KEY');

        if (!$headerKey || $headerKey !== config('app.artisan_http_key')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
