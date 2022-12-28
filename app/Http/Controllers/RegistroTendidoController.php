<?php

namespace App\Http\Controllers;

use App\Models\RegistroTendido;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class RegistroTendidoController extends Controller
{
    private $entidad = 'Registro tendido';

    public function index()
    {
        $results = RegistroTendido::all();
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['tendido_id'] = $datos['tendido'];

        $modelo = RegistroTendido::create($datos);
        return response()->json(compact('modelo'));
    }

    public function show(RegistroTendido $registroTendido)
    {
        $modelo = $registroTendido;
        return response()->json(compact('modelo'));
    }

    public function update(Request $request, RegistroTendido $registroTendido)
    {
        $registroTendido->update($request->all());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        $modelo = $registroTendido;
        return response()->json(compact('mensaje', 'modelo'));
    }
}
