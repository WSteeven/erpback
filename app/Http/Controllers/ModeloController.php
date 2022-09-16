<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeloRequest;
use App\Http\Resources\ModeloResource;
use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ModeloController extends Controller
{
    private $entidad = 'Modelo';

    /**
     * Listar
     */
    public function index()
    {
        $results = ModeloResource::collection(Modelo::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(ModeloRequest $request)
    {
        Log::channel('testing')->info('Log', ['request_recibida', $request]);
        
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['marca_id'] = $request->safe()->only(['marca'])['marca'];
        
        //Respuesta
        $modelo = Modelo::create($datos);
        //$modelo = Modelo::create($request->validated());
        $modelo = new ModeloResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Modelo $modelo)
    {
        $modelo = new ModeloResource($modelo);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(ModeloRequest $request, Modelo  $modelo)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['marca_id'] = $request->safe()->only(['marca'])['marca'];

        //Respuesta
        $modelo->update($datos);
        $modelo = new ModeloResource($modelo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Modelo $modelo)
    {
        $modelo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
