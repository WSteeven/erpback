<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserController;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckUserDesactivado
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
        if (auth()->check() && (!auth()->user()->status)) {
            $request->session()->invalidate();

            $request->session()->regenerateToken();
            $error = 'Tu cuenta ha sido desactivada';
            return response()->json(['mensaje' => $error], 401);
        }

        return $next($request);
    }
}
