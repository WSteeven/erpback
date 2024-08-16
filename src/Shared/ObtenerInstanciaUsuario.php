<?php

namespace Src\Shared;

use App\Models\RecursosHumanos\SeleccionContratacion\UserExternal;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ObtenerInstanciaUsuario
{

    /**
 * Determina el tipo de usuario autenticado y recupera su ID.
 *
 * @return array [$user_id, $user_type]
 * @throws Exception Si el usuario no está autenticado a través de la API
 */
public static function tipoUsuario()
{
    Log::channel('testing')->info('Log', ['ObtenerInstanciaUsuario::tipoUsuario', Auth::guard('sanctum')->check(), Auth::guard('sanctum')->user() ]);
    $user_id = null;
    $user_type = null;

    // Determina el usuario autenticado
    if (Auth::guard('sanctum')->check()) {
        $user_id = Auth::guard('sanctum')->user()->id;

        // Comprueba el tipo de usuario
        if (Auth::guard('sanctum')->user() instanceof User)
            $user_type = User::class;
        else if (Auth::guard('sanctum')->user() instanceof UserExternal)
            $user_type = UserExternal::class;
    }
    // else {
    //     // Lanza una excepción si el usuario no está autenticado a través de la API
    //     throw new Exception('Usuario no logueado por API');
    // }

    // Devuelve el ID y el tipo del usuario
    return [$user_id, $user_type];
}
}
