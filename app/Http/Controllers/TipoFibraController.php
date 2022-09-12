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
        $results = TipoFibra::collection(TipoFibra::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(TipoFibraRequest $request)
    {
        //Respuesta
        $modelo = TipoFibra::create($request->validated());
        $modelo = new TipoFibra($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(TipoFibra $tipoFibra)
    {
        $modelo = new TipoFibraResource($tipoFibra);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(TipoFibraRequest $request, TipoFibra  $tipoFibra)
    {
        //Respuesta
        $tipoFibra->update($request->validated());
        $modelo = new TipoFibraResource($tipoFibra->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(TipoFibra $tipoFibra)
    {
        $tipoFibra->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
