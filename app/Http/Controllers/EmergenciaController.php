<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmergenciaRequest;
use App\Http\Resources\EmergenciaResource;
use App\Models\Emergencia;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class EmergenciaController extends Controller
{
    private $entidad = 'Emergencia';

    public function store(EmergenciaRequest $request)
    {
        // $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
        //$modelo = new EmergenciaResource($modelo);
        $modelo = Emergencia::create($request->all());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
}
