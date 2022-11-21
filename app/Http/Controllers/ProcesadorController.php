<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcesadorRequest;
use App\Http\Resources\ProcesadorResource;
use App\Models\Procesador;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ProcesadorController extends Controller
{
    private $entidad = 'Procesador';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $results = [];
        
        if ($request['campos']) {
            $results = Procesador::all($campos);
            // $results = ProcesadorResource::collection($results);
            // return $results;
        } else
        
        if ($page) {
            $results = Procesador::simplePaginate($request['offset']);
            // ProcesadorResource::collection($results);
            // $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Procesador::all();
            // ProcesadorResource::collection($results);
        }
        ProcesadorResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(ProcesadorRequest $request)
    {
        //Respuesta 
        $modelo = Procesador::create($request->validated());
        $modelo = new ProcesadorResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Procesador $procesador)
    {
        $modelo = new ProcesadorResource($procesador);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */

    public function update(ProcesadorRequest $request, Procesador $procesador)
    {
        //Respuesta
        $procesador->update($request->validated());
        $modelo = new ProcesadorResource($procesador->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Procesador $procesador)
    {
        $procesador->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
