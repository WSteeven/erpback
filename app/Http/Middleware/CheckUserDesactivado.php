<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

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
        if (auth()->check()) {
            $user = auth()->user();

            if ($user instanceof User && !$this->tieneEmpleadoActivo($user)) {
                $this->invalidarSesion($request);
            }
        }

        return $next($request);
    }

    /**
     * La función comprueba si un usuario tiene un empleado activo asociado.
     * 
     * @param User $user instancia de `App\Models\User` sobre la que se verifica.
     * 
     * @return bool Devuelve un valor booleano si user tiene un objeto `empleado`
     * asociado y si la propiedad `estado` del objeto `empleado` es veraz.
     */
    private function tieneEmpleadoActivo(User $user)
    {
        return $user->empleado && $user->empleado->estado;
    }

    /**
     * La función invalida la sesión, regenera el token y devuelve una respuesta JSON que indica que la
     * cuenta ha sido desactivada.
     * 
     * @param Request $request El parámetro `request` en la función `invalidarSesion` es una instancia
     * de la clase `Illuminate\Http\Request`. Se utiliza para acceder a la solicitud HTTP entrante,
     * incluidos los datos o parámetros enviados con la solicitud.
     * 
     * @return json  La función `invalidarSesion` devuelve una respuesta JSON con un mensaje que indica que
     * la cuenta del usuario ha sido desactivada. El código de estado HTTP para esta respuesta es 401,
     * lo que significa acceso no autorizado.
     */
    private function invalidarSesion(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $error = 'Tu cuenta ha sido desactivada';
        return response()->json(['mensaje' => $error], 401);
    }
}
