<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\UmbralVentasRequest;
use App\Http\Resources\Ventas\UmbralVentasResource;
use App\Models\Ventas\UmbralVentas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class UmbralVentasController extends Controller
{
    private $entidad = 'Umbral Ventas';
    public function __construct()
    {
        $this->middleware('can:puede.ver.umbral_ventas')->only('index', 'show');
        $this->middleware('can:puede.crear.umbral_ventas')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = UmbralVentas::ignoreRequest(['campos'])->filter()->with('vendedor')->get();
         $results = UmbralVentasResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, UmbralVentas $umbral_venta)
    {
        $modelo = new UmbralVentasResource($umbral_venta);
        return response()->json(compact('modelo'));
    }
    public function store(UmbralVentasRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral_venta = UmbralVentas::create($datos);
            $modelo = new UmbralVentasResource($umbral_venta);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(UmbralVentasRequest $request, UmbralVentas $umbral_venta)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral_venta->update($datos);
            $modelo = new UmbralVentasResource($umbral_venta->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, UmbralVentas $umbral_venta)
    {
        $umbral_venta->delete();
        return response()->json(compact('UmbralVentas'));
    }
}
