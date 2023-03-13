<?php

namespace App\Http\Controllers;

use App\Models\MotivoPendiente;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MotivoPendienteController extends Controller
{
    private $entidad = 'Motivo de pendiente';

    public function index() {
        $results = MotivoPendiente::where('activo', true)->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request) {
        $request->validate(['motivo' => 'required', 'activo' => 'nullable|boolean']);
        $modelo = MotivoPendiente::create($request->all());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(Request $request, MotivoPendiente $motivo_pendiente) {
        $request->validate(['motivo' => 'required', 'activo' => 'required|boolean']);
        $motivo_pendiente->update($request->all());
        $modelo = $motivo_pendiente->refresh();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(MotivoPendiente $motivo_pendiente) {
        $modelo = $motivo_pendiente;
        return response()->json(compact('modelo'));
    }
}
