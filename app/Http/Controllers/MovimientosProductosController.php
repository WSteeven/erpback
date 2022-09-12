<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimientosProductosRequest;
use App\Http\Resources\MovimientosProductosResource;
use App\Models\MovimientosProductos;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MovimientosProductosController extends Controller
{
    private $entidad = 'Movimiento de Producto';

    /**
     * Listar
     */
    public function index()
    {
        $results = MovimientosProductosResource::collection(MovimientosProductos::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(Request $request)
    {
        //Respuesta
        $modelo = MovimientosProductos::create($request->validated());
        $modelo = new MovimientosProductosResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(MovimientosProductos $movimiento)
    {
        $modelo = new MovimientosProductosResource($movimiento);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(MovimientosProductosRequest $request, MovimientosProductos $movimiento)
    {
        //Respuesta
        $movimiento->update($request->validated());
        $modelo = new MovimientosProductosResource($movimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(MovimientosProductos $movimiento)
    {
        $movimiento->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
