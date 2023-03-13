<?php

namespace App\Http\Controllers;

use App\Models\MotivoPausa;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MotivoPausaController extends Controller
{
    private $entidad = 'Motivo de pausa';

    public function index() {
        $results = MotivoPausa::where('activo', true)->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request) {
        $request->validate(['motivo' => 'required', 'activo' => 'nullable|boolean']);
        $modelo = MotivoPausa::create($request->all());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(Request $request, MotivoPausa $motivo_pausa) {
        $request->validate(['motivo' => 'required', 'activo' => 'required|boolean']);
        $motivo_pausa->update($request->all());
        $modelo = $motivo_pausa->refresh();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(MotivoPausa $motivo_pausa) {
        $modelo = $motivo_pausa;
        return response()->json(compact('modelo'));
    }
}
