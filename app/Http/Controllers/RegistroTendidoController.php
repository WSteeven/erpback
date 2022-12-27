<?php

namespace App\Http\Controllers;

use App\Models\RegistroTendido;
use Illuminate\Http\Request;

class RegistroTendidoController extends Controller
{
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
}
