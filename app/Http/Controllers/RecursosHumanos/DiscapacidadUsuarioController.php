<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use Src\Shared\ObtenerInstanciaUsuario;
use Src\Shared\Utils;

class DiscapacidadUsuarioController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function discapacidadesUsuario()
    {
        try {
            [, , $user] = ObtenerInstanciaUsuario::tipoUsuario();
            $results = $user->discapacidades()->get();
            $results = $results->map(function ($item) {
                return ['id' => $item->id,
                    'tipo_discapacidad' => $item->tipo_discapacidad_id,
                    'porcentaje' => $item->porcentaje];
            });
            return response()->json(compact('results'));
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }
}
