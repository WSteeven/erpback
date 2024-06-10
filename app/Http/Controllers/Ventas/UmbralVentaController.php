<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\UmbralVentaRequest;
use App\Http\Resources\Ventas\UmbralVentaResource;
use App\Models\Ventas\UmbralVenta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class UmbralVentaController extends Controller
{
    private $entidad = 'Umbral Ventas';
    public function __construct()
    {
        $this->middleware('can:puede.ver.umbrales_ventas')->only('index', 'show');
        $this->middleware('can:puede.crear.umbrales_ventas')->only('store');
        $this->middleware('can:puede.editar.umbrales_ventas')->only('update');
        $this->middleware('can:puede.eliminar.umbrales_ventas')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = UmbralVenta::ignoreRequest(['campos'])->filter()->with('vendedor')->get();
         $results = UmbralVentaResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, UmbralVenta $umbral_venta)
    {
        $modelo = new UmbralVentaResource($umbral_venta);
        return response()->json(compact('modelo'));
    }
    public function store(UmbralVentaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral_venta = UmbralVenta::create($datos);
            $modelo = new UmbralVentaResource($umbral_venta);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(UmbralVentaRequest $request, UmbralVenta $umbral_venta)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral_venta->update($datos);
            $modelo = new UmbralVentaResource($umbral_venta->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, UmbralVenta $umbral_venta)
    {
        $umbral_venta->delete();
        return response()->json(compact('UmbralVenta'));
    }
}
