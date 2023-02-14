<?php

namespace App\Http\Controllers\FondosRotativos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FondosRotativos\Viatico\TipoFondo;
use App\Http\Resources\FondosRotativos\TipoFondoResource;

class TipoFondoController extends Controller
{
    public function __construct()
    {
        //['index','noticias']
        //$this->middleware('guest');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = TipoFondo::ignoreRequest(['campos'])->filter()->get();
        $results = TipoFondoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function store(Request $request)
    {
        $datos = $request->all();
        $tipo_fondo = TipoFondo::create($datos);
        return response()->json($tipo_fondo, 201);
    }

    public function show($id)
    {
        $tipo_fondo = TipoFondo::findOrFail($id);
        return response()->json($tipo_fondo, 200);
    }

    public function update(Request $request, $id)
    {
        $tipo_fondo = TipoFondo::findOrFail($id);
        $tipo_fondo->update($request->all());
        return response()->json($tipo_fondo, 200);
    }

    public function destroy($id)
    {
        $tipo_fondo = TipoFondo::findOrFail($id);
        $tipo_fondo->delete();
        return response()->json(null, 204);
    }
}
