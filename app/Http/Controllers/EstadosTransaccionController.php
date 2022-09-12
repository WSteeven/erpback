<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstadosTransaccionRequest;
use App\Http\Resources\EstadosTransaccionResource;
use App\Models\EstadosTransaccion;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class EstadosTransaccionController extends Controller
{
    private $entidad = 'Estado';

    /**
     * Listar
     */
    public function index()
    {
        $results = EstadosTransaccionResource::collection(EstadosTransaccion::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(EstadosTransaccionRequest $request)
    {
        $modelo = EstadosTransaccion::create($request->validated());
        $modelo = new EstadosTransaccionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(EstadosTransaccion $estado)
    {
        $modelo = new EstadosTransaccionResource($estado);
        return response()->json(compact('modelo'));
    }

/**
 * Actualizar
 */
    public function update(EstadosTransaccionRequest $request, EstadosTransaccion  $estado)
    {
        //Respuesta
        $estado->update($request->validated());
        $modelo = new EstadosTransaccionResource($estado->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(EstadosTransaccion $estado)
    {
        $estado->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
