<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /* public function __construct()
    {
        $this->middleware('can:puede.ver.productos')->only('index', 'show');
        $this->middleware('can:puede.crear.productos')->only('store');
    } */


    public function index()
    {
        $productos = ProductoResource::collection(Producto::all());
        return response()->json(['productos' => $productos]);
    }

    public function store(ProductoRequest $request)
    {
        $productoCreado = Producto::create($request->validated());
        return response()->json(['mensaje' => 'Producto creado', 'modelo' => new ProductoResource($productoCreado)]);
    }

    public function show(Producto $producto)
    {
        return response()->json(['modelo' => new ProductoResource($producto)]);
    }


    public function update(ProductoRequest $request, Producto $producto)
    {
        $datosValidados = $request->validated();

        $producto->update($datosValidados);
        return response()->json(['mensaje' => 'Producto actualizado con exito', 'modelo' => new ProductoResource($producto)]);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json(['mensaje' => 'Producto eliminado con exito']);
    }
}
