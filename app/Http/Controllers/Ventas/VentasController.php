<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\VentasRequest;
use App\Http\Resources\Ventas\VentasResource;
use App\Models\Ventas\Ventas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class VentasController extends Controller
{
    private $entidad = 'Ventas';
    public function __construct()
    {
        $this->middleware('can:puede.ver.ventas')->only('index', 'show');
        $this->middleware('can:puede.crear.ventas')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Ventas::ignoreRequest(['campos'])->filter()->get();
        $results = VentasResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Ventas $venta)
    {
        $modelo = new VentasResource($venta);
        return response()->json(compact('modelo'));
    }

    public function store(VentasRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral = Ventas::create($datos);
            $modelo = new VentasResource($umbral);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(VentasRequest $request, Ventas $umbral)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral->update($datos);
            $modelo = new VentasResource($umbral->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Ventas $umbral)
    {
        $umbral->delete();
        return response()->json(compact('umbral'));
    }
}
