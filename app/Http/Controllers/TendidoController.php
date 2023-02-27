<?php

namespace App\Http\Controllers;

use App\Http\Resources\TendidoResource;
use App\Models\Tendido;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TendidoController extends Controller
{
    public function index()
    {
        $results = Tendido::all();
        return response(compact('results'));
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['bobina_id'] = $request['bobina'];
        $datos['trabajo_id'] = $request['trabajo'];

        $modelo = Tendido::create($datos);
        $mensaje = 'Iniciado exitosamente!';
        return response(compact('modelo', 'mensaje'));
    }

    public function show($trabajo)
    {
        $modelo = Tendido::where('trabajo_id', $trabajo)->first();

        if (!$modelo) {
            throw ValidationException::withMessages([
                'no_encontrado' => ['No existe control de tendido para esta subtarea.'],
            ]);
        }

        $modelo = new TendidoResource($modelo);
        return response()->json(compact('modelo'));
    }
}
