<?php

namespace App\Http\Controllers;


use App\Http\Requests\EstadoTransaccionRequest;
use App\Http\Resources\EstadoTransaccionResource;
use App\Models\EstadoTransaccion;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class EstadoTransaccionController extends Controller
{
    private $entidad = 'Estado';

    /**
     * Listar
     */
    public function index()
    {
        $results = EstadoTransaccionResource::collection(EstadoTransaccion::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(EstadoTransaccionRequest $request)
    {
        $modelo = EstadoTransaccion::create($request->validated());
        $modelo = new EstadoTransaccionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(EstadoTransaccion $estado)
    {
        $modelo = new EstadoTransaccionResource($estado);
        return response()->json(compact('modelo'));
    }

/**
 * Actualizar
 */
    public function update(EstadoTransaccionRequest $request, EstadoTransaccion  $estado)
    {
        //Respuesta
        $estado->update($request->validated());
        $modelo = new EstadoTransaccionResource($estado->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(EstadoTransaccion $estado)
    {
        $estado->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
