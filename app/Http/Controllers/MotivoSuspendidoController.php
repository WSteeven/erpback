<?php

namespace App\Http\Controllers;

use App\Models\MotivoSuspendido;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MotivoSuspendidoController extends Controller
{
    private $entidad = 'Motivo de suspendido';

    public function index() {
        $results = MotivoSuspendido::where('activo', true)->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request) {
        $request->validate(['motivo' => 'required', 'activo' => 'nullable|boolean']);
        $modelo = MotivoSuspendido::create($request->all());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(Request $request, MotivoSuspendido $motivo_suspendido) {
        $request->validate(['motivo' => 'required', 'activo' => 'required|boolean']);
        $motivo_suspendido->update($request->all());
        $modelo = $motivo_suspendido->refresh();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(MotivoSuspendido $motivo_suspendido) {
        $modelo = $motivo_suspendido;
        return response()->json(compact('modelo'));
    }
}
