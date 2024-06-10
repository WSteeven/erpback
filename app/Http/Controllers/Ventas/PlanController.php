<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\PlanRequest;
use App\Http\Resources\Ventas\PlanResource;
use App\Models\Ventas\Plan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class PlanController extends Controller
{
    private $entidad = 'Plan';
    public function __construct()
    {
        $this->middleware('can:puede.ver.planes')->only('index', 'show');
        $this->middleware('can:puede.crear.planes')->only('store');
        $this->middleware('can:puede.editar.planes')->only('update');
        $this->middleware('can:puede.eliminar.planes')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Plan::ignoreRequest(['campos'])->filter()->get();
        $results = PlanResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Plan $plan)
    {
        $modelo = new PlanResource($plan);

        return response()->json(compact('modelo'));
    }
    public function store(PlanRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $plan = Plan::create($datos);
            $modelo = new PlanResource($plan);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(PlanRequest $request, Plan $plan)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $plan->update($datos);
            $modelo = new PlanResource($plan->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Plan $plan)
    {
        $plan->delete();
        return response()->json(compact('umbral'));
    }
}
