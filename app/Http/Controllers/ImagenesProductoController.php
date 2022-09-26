<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImagenesProductoRequest;
use App\Http\Resources\ImagenesProductoResource;
use App\Models\ImagenesProducto;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ImagenesProductoController extends Controller
{
    private $entidad = 'Imagen de producto';

    /**
     * Listar
     */
    public function index()
    {
        $results = ImagenesProductoResource::collection(ImagenesProducto::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(ImagenesProductoRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['detalle_id'] = $request->safe()->only(['detalle'])['detalle'];
        //Respuesta
        $modelo = ImagenesProducto::create($datos);
        $modelo = new ImagenesProductoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(ImagenesProducto $imagenproducto)
    {
        $modelo = new ImagenesProductoResource($imagenproducto);
        return response()->json(compact('modelo'));
    }

/**
 * Actualizar
 */
    public function update(ImagenesProductoRequest $request, ImagenesProducto  $imagenproducto)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['detalle_id'] = $request->safe()->only(['detalle'])['detalle'];
        
        //Respuesta
        $imagenproducto->update($datos);
        $modelo = new ImagenesProductoResource($imagenproducto->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

/**
 * Eliminar
 */
    public function destroy(ImagenesProducto $imagenproducto)
    {
        $imagenproducto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
