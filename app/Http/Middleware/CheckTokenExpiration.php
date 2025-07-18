<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()?->currentAccessToken();

        if($token && $token->expires_at && $token->expires_at->isPast()) {
            $token->delete();

            return response()->json(['mensaje' => 'Token expirado. Por favor, vuelva a iniciar sesión.'], 401);
        }

        return $next($request);
    }
}
