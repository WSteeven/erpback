<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoTransaccionRequest;
use App\Http\Resources\TipoTransaccionResource;
use App\Models\TipoTransaccion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Src\Shared\Utils;

class TipoTransaccionController extends Controller
{
    private $entidad = 'Tipo de transaccion';
    /**
     * Listar
     */
    public function index()
    {
        $results = TipoTransaccionResource::collection(TipoTransaccion::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoTransaccionRequest $request)
    {
        //Respuesta
        $modelo = TipoTransaccion::create($request->validated());
        $modelo = new TipoTransaccionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoTransaccion $tipo)
    {
        $modelo = new TipoTransaccionResource($tipo);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TipoTransaccionRequest $request, TipoTransaccion  $tipo)
    {
        
        $tipo->update($request->all());

        return response()->json(['mensaje' => 'El tipo ha sido actualizado con éxito', 'modelo' => $tipo]);
    }

    /**
     * Eliminar
     */
    public function destroy(TipoTransaccion $tipo)
    {
        $tipo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
