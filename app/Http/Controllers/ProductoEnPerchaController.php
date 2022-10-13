<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoEnPerchaRequest;
use App\Http\Resources\ProductoEnPerchaResource;
use App\Models\ProductoEnPercha;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ProductoEnPerchaController extends Controller
{
    private $entidad = 'Productos en Percha';
    public function __construct()
    {
        $this->middleware('can:puede.ver.productos_perchas')->only('index', 'show');
        $this->middleware('can:puede.crear.productos_perchas')->only('store');
        $this->middleware('can:puede.editar.productos_perchas')->only('update');
        $this->middleware('can:puede.eliminar.productos_perchas')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = ProductoEnPerchaResource::collection(ProductoEnPercha::all());
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductoEnPerchaRequest $request)
    {
        $datos = $request->validated();
        $datos['inventario_id'] = $request->safe()->only(['inventario'])['inventario'];
        $datos['ubicacion_id'] = $request->safe()->only(['ubicacion'])['ubicacion'];

        $modelo = ProductoEnPercha::create($datos);
        $modelo = new ProductoEnPerchaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductoEnPercha $producto_en_percha)
    {
        $modelo = new ProductoEnPerchaResource($producto_en_percha);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductoEnPerchaRequest $request, ProductoEnPercha $producto_en_percha)
    {
        $datos = $request->validated();

        $producto_en_percha->update($datos);
        $modelo = new ProductoEnPerchaResource($producto_en_percha->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductoEnPercha $producto_en_percha)
    {
        $producto_en_percha->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
