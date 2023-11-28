<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tareas\EtapaResource;
use App\Models\Tareas\Etapa;
use Illuminate\Http\Request;

class EtapaController extends Controller
{
    private $entidad = 'Etapa';

    /**
     * Listar
     */
    public function index(Request $request){
        $results  = Etapa::filter()->get();
        $results = EtapaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Consultar
     */
    public function show(Etapa $etapa)
    {
        $modelo = new EtapaResource($etapa);
        return response()->json(compact('modelo'));
    }

    public function desactivar(Etapa $etapa){
        $etapa->activo  = !$etapa->activo;
        $etapa->save();

        $modelo = new EtapaResource($etapa->refresh());
        return response()->json(compact('modelo'));
    }

}
