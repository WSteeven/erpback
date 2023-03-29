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
        $datos['subtarea_id'] = $request['subtarea'];

        $modelo = Tendido::create($datos);
        $mensaje = 'Iniciado exitosamente!';
        return response(compact('modelo', 'mensaje'));
    }

    public function show($subtarea)
    {
        $modelo = Tendido::where('subtarea_id', $subtarea)->first();

        if (!$modelo) {
            throw ValidationException::withMessages([
                'no_encontrado' => ['Seleccione una bobina para iniciar con el trabajo.'],
            ]);
        }

        $modelo = new TendidoResource($modelo);
        return response()->json(compact('modelo'));
    }
}
