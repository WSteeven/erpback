<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Models\CodigoCliente;
use App\Models\Producto;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.productos')->only('index', 'show');
        $this->middleware('can:puede.crear.productos')->only('store');
        $this->middleware('can:puede.editar.productos')->only('update');
        $this->middleware('can:puede.eliminar.productos')->only('destroy');

    }
    public function generarCodigo($id)
    {
        $codigo = "";
        while (strlen($codigo) < (6 - strlen($id))) {
            $codigo .= "0";
        }
        $codigo .= strval($id);
        return $codigo;
    }


    public function index()
    {
        $productos = ProductoResource::collection(Producto::all());
        return response()->json(['productos' => $productos]);
    }

    public function store(ProductoRequest $request)
    {

        $productoCreado = Producto::create($request->validated());
        /* $codigoCliente = CodigoCliente::create([
            "propietario_id" => 1,
            "producto_id" => $productoCreado->id,
            "codigo" => $this->generarCodigo($productoCreado->id)
        ]); */
        return response()->json([
            'mensaje' => 'El producto ha sido creado con éxito',
            'modelo' => new ProductoResource($productoCreado),
            /* "codigo" => $codigoCliente */
        ]);
    }



    public function show(Producto $producto)
    {
        return response()->json(['modelo' => new ProductoResource($producto)]);
    }


    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());
        return response()->json(['mensaje' => 'El producto ha sido actualizado con éxito', 'modelo' => new ProductoResource($producto)]);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json(['mensaje' => 'El producto ha sido eliminado con éxito', 'modelo'=>new ProductoResource($producto)]);
    }
}
