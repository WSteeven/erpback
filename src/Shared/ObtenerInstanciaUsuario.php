<?php

namespace Src\Shared;

use App\Models\RecursosHumanos\SeleccionContratacion\UserExternal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ObtenerInstanciaUsuario
{

    /**
     * Determina el tipo de usuario autenticado y recupera su ID.
     *
     * @return array [$user_id, $user_type]
     */
    public static function tipoUsuario()
    {
//        Log::channel('testing')->info('Log', ['ObtenerInstanciaUsuario::tipoUsuario', Auth::guard('sanctum')->check(), Auth::guard('sanctum')->user()]);
//        Log::channel('testing')->info('Log', ['$user = auth()->user()->getAuthIdentifier();', auth()->user()->getAuthIdentifier()]);
//        Log::channel('testing')->info('Log', ['$user = auth()->user()->getAutIdentifierhName();', auth()->user()->getAuthIdentifierName()]);
//        Log::channel('testing')->info('Log', ['$user = auth()->user()->getAuthPassword();', auth()->user()->getAuthPassword()]);
        $user_id = null;
        $user_type = null;
        $user = null;

        // Determina el usuario autenticado
        if (Auth::guard('sanctum')->check()) {
            $user_id = Auth::guard('sanctum')->user()->id ?? null;

            // Comprueba el tipo de usuario
            if (Auth::guard('sanctum')->user() instanceof User) {
                $user_type = User::class;
                $user = User::find($user_id);
            } else if (Auth::guard('sanctum')->user() instanceof UserExternal) {
                $user_type = UserExternal::class;
                $user = UserExternal::find($user_id);
            }
        }

        // Devuelve el ID, el tipo del usuario y el usuario
        return [$user_id, $user_type, $user];
    }
}
