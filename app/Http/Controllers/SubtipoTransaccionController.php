<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubtipoTransaccionRequest;
use App\Http\Resources\SubtipoTransaccionResource;
use App\Models\SubtipoTransaccion;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class SubtipoTransaccionController extends Controller
{
    private $entidad = 'Subtipo de transaccion';

    /**
     * Listar todos los subtipos de transacciones
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = SubtipoTransaccionResource::collection(SubtipoTransaccion::all());
        return response()->json(compact('results'));        
    }

    /**
     * Guardar un nuevo subtipo de transaccion
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubtipoTransaccionRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_transaccion_id'] = $request->safe()->only(['tipo_transaccion'])['tipo_transaccion'];
        
        //Respuesta 
        $modelo = SubtipoTransaccion::create($datos);
        $modelo = new SubtipoTransaccionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar un recurso especifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SubtipoTransaccion $subtipo_transaccion)
    {
        $modelo = new SubtipoTransaccionResource($subtipo_transaccion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar el recurso especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubtipoTransaccionRequest $request, SubtipoTransaccion $subtipo_transaccion)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_transaccion_id'] = $request->safe()->only(['tipo_transaccion'])['tipo_transaccion'];
        
        //Respuesta
        $subtipo_transaccion->update($datos);
        $modelo = new SubtipoTransaccionResource($subtipo_transaccion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar el recurso especifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubtipoTransaccion $subtipo_transaccion)
    {
        $subtipo_transaccion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
