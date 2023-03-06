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

    public function index(){
        $results = Emergencia::filter()->get();
        return response()->json(compact('results'));
    }

    public function store(EmergenciaRequest $request)
    {
        $datos = $request->validated();
        $datos['subtarea_id'] = $request->safe()->only(['trabajo'])['trabajo'];
        //$modelo = new EmergenciaResource($modelo);
        $modelo = Emergencia::create($datos);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
}
