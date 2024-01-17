<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ProductoVentaRequest;
use App\Http\Resources\Ventas\ProductoVentaResource;
use App\Models\Ventas\ProductoVenta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ProductoVentaController extends Controller
{
    private $entidad = 'Producto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.productos_ventas')->only('index', 'show');
        $this->middleware('can:puede.crear.productos_ventas')->only('store');
        $this->middleware('can:puede.editar.productos_ventas')->only('update');
        $this->middleware('can:puede.eliminar.productos_ventas')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = ProductoVenta::ignoreRequest(['campos'])->filter()->get();
        $results = ProductoVentaResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request,  $productoVendedor)
    {
        $productoVendedor = ProductoVenta::where('id',$productoVendedor)->first();
        $modelo = new ProductoVentaResource($productoVendedor);
        return response()->json(compact('modelo'));
    }
    public function store(ProductoVentaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral = ProductoVenta::create($datos);
            $modelo = new ProductoVentaResource($umbral);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(ProductoVentaRequest $request, ProductoVenta $umbral)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral->update($datos);
            $modelo = new ProductoVentaResource($umbral->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, ProductoVenta $umbral)
    {
        $umbral->delete();
        return response()->json(compact('umbral'));
    }
}
