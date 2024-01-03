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
        $this->middleware('can:puede.ver.planes')->only('index', 'show');
        $this->middleware('can:puede.crear.planes')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Planes::ignoreRequest(['campos'])->filter()->get();
        $results = PlanesResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Planes $plan)
    {
        $modelo = new PlanesResource($plan);

        return response()->json(compact('modelo'));
    }
    public function store(PlanesRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $plan = Planes::create($datos);
            $modelo = new PlanesResource($plan);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(PlanesRequest $request, Planes $plan)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $plan->update($datos);
            $modelo = new PlanesResource($plan->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Planes $plan)
    {
        $plan->delete();
        return response()->json(compact('umbral'));
    }
}
