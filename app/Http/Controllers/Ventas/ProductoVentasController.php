<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ProductoVentasRequest;
use App\Http\Resources\Ventas\ProductoVentasResource;
use App\Models\Ventas\ProductoVentas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ProductoVentasController extends Controller
{
    private $entidad = 'Producto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.producto_ventas')->only('index', 'show');
        $this->middleware('can:puede.crear.producto_ventas')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = ProductoVentas::ignoreRequest(['campos'])->filter()->get();
        $results = ProductoVentasResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request,  $productoVendedor)
    {
        $productoVendedor = ProductoVentas::where('id',$productoVendedor)->first();
        $model = new ProductoVentasResource($productoVendedor);
        return response()->json(compact('model'));
    }
    public function store(ProductoVentasRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral = ProductoVentas::create($datos);
            $modelo = new ProductoVentasResource($umbral);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(ProductoVentasRequest $request, ProductoVentas $umbral)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral->update($datos);
            $modelo = new ProductoVentasResource($umbral->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, ProductoVentas $umbral)
    {
        $umbral->delete();
        return response()->json(compact('umbral'));
    }
}
