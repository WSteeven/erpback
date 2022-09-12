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
        $request->validate(['url' => 'required|unique:imagenes_productos','producto_id'=>'required|exists:nombres_de_productos,id']);
        //Respuesta
        $modelo = ImagenesProducto::create($request->validated());
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
        $request->validate(['url' => 'required|unique:imagenes_productos','producto_id'=>'required|exists:nombres_de_productos,id']);
        //Respuesta
        $imagenproducto->update($request->validated());
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
