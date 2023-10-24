<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\PlanesRequest;
use App\Http\Resources\Ventas\PlanesResource;
use App\Models\Ventas\Planes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class PlanesController extends Controller
{
    private $entidad = 'Planes';
    public function __construct()
    {
        $this->middleware('can:puede.ver.umbral_fondos_rotativos')->only('index', 'show');
        $this->middleware('can:puede.crear.umbral_fondos_rotativos')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Planes::ignoreRequest(['campos'])->filter()->get();
        $results = PlanesResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Planes $umbral)
    {
        $results = new PlanesResource($umbral);

        return response()->json(compact('results'));
    }
    public function store(PlanesRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral = Planes::create($datos);
            $modelo = new PlanesResource($umbral);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(PlanesRequest $request, Planes $umbral)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral->update($datos);
            $modelo = new PlanesResource($umbral->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Planes $umbral)
    {
        $umbral->delete();
        return response()->json(compact('umbral'));
    }
}
