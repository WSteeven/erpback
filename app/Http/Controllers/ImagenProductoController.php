<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImagenProductoRequest;
use App\Http\Resources\ImagenProductoResource;
use App\Models\ImagenProducto;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ImagenProductoController extends Controller
{
    private $entidad = 'Imagen de producto';

    /**
     * Listar
     */
    public function index()
    {
        $results = ImagenProductoResource::collection(ImagenProducto::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(ImagenProductoRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['detalle_id'] = $request->safe()->only(['detalle'])['detalle'];
        //Respuesta
        $modelo = ImagenProducto::create($datos);
        $modelo = new ImagenProductoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(ImagenProducto $imagenproducto)
    {
        $modelo = new ImagenProductoResource($imagenproducto);
        return response()->json(compact('modelo'));
    }

/**
 * Actualizar
 */
    public function update(ImagenProductoRequest $request, ImagenProducto  $imagenproducto)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['detalle_id'] = $request->safe()->only(['detalle'])['detalle'];
        
        //Respuesta
        $imagenproducto->update($datos);
        $modelo = new ImagenProductoResource($imagenproducto->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

/**
 * Eliminar
 */
    public function destroy(ImagenProducto $imagenproducto)
    {
        $imagenproducto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
