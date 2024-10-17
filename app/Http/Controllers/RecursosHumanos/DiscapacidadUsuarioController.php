<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\DiscapacidadUsuario;
use Exception;
use Illuminate\Validation\ValidationException;
use Src\Shared\ObtenerInstanciaUsuario;
use Src\Shared\Utils;

class DiscapacidadUsuarioController extends Controller
{
    /**
     * Obtiene las discapacidades del usuario logueado
     * @throws ValidationException
     */
    public function discapacidadesUsuario()
    {
        try {
            [, , $user] = ObtenerInstanciaUsuario::tipoUsuario();
            $results = $user->discapacidades()->get();
            $results = DiscapacidadUsuario::mapearDiscapacidades($results);
            return response()->json(compact('results'));
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }
}
