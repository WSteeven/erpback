<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserController;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

use function PHPUnit\Framework\isNull;

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
        $user = auth()->user();

        if (is_null($user?->empleado)) {
            if ($user && !$user->postulante->estado) {
                return $this->invalidateSession($request, 'Tu cuenta ha sido desactivada');
            }
        } elseif ($user && !$user->empleado->estado) {
            return $this->invalidateSession($request, 'Tu cuenta ha sido desactivada');
        }

        return $next($request);
    }

  /**
   * La función `invalidateSession` invalida la sesión actual, regenera el token de sesión y devuelve
   * una respuesta JSON con un mensaje específico y un código de estado 401.
   *
   * @param Request request El parámetro `` es una instancia de la clase
   * `Illuminate\Http\Request` en Laravel. Representa una solicitud HTTP y contiene información sobre
   * la solicitud, como el método de solicitud, los encabezados y los datos de entrada.
   * @param message El parámetro `` en la función `invalidateSession` es un mensaje
   * personalizado que se devolverá en la respuesta JSON. Se utiliza para proporcionar información o
   * comentarios al cliente sobre por qué se invalidó la sesión.
   *
   * @return La función `invalidateSession` devuelve una respuesta JSON con un mensaje y un código de
   * estado 401 (no autorizado).
   */
    private function invalidateSession(Request $request, $message)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['mensaje' => $message], 401);
    }
}
