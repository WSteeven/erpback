<?php

namespace App\Http\Controllers;

use App\Models\Tendido;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TendidoController extends Controller
{
    // private $entidad = '';

    public function index()
    {
        $results = Tendido::all();
        return response(compact('results'));
    }

    public function store(Request $request)
    {
        $datos = $request->all(); //validated();
        $datos['bobina_id'] = $request['bobina'];
        $datos['subtarea_id'] = $request['subtarea'];

        $modelo = Tendido::create($datos);
        $mensaje = 'Iniciado exitosamente!'; //Utils::obtenerMensaje($this->entidad, 'store', false);
        return response(compact('modelo', 'mensaje'));
    }

    public function show($subtarea) {
        $modelo = Tendido::where('subtarea_id', $subtarea)->first();

        if (!$modelo) {
            throw ValidationException::withMessages([
                'no_encontrado' => ['No existe control de tendido para esta subtarea.'],
            ]);
        }

        return response()->json(compact('modelo'));
    }
}
