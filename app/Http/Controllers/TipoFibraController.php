<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoFibraRequest;
use App\Http\Resources\TipoFibraResource;
use App\Models\TipoFibra;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class TipoFibraController extends Controller
{
    private $entidad = 'Tipo de fibra';

    /**
     * Listar
     */
    public function index()
    {
        $results = TipoFibraResource::collection(TipoFibra::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(TipoFibraRequest $request)
    {
        //Respuesta
        $modelo = TipoFibra::create($request->validated());
        $modelo = new TipoFibraResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(TipoFibra $tipo_fibra)
    {
        $modelo = new TipoFibraResource($tipo_fibra);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(TipoFibraRequest $request, TipoFibra  $tipo_fibra)
    {
        //Respuesta
        $tipo_fibra->update($request->validated());
        $modelo = new TipoFibraResource($tipo_fibra->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('modelo', 'mensaje'));
    }


    /**
     * Eliminar
     */
    public function destroy(TipoFibra $tipo_fibra)
    {
        $tipo_fibra->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
