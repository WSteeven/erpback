<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\PlanVacacionRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PlanVacacionResource;
use App\Models\RecursosHumanos\NominaPrestamos\PlanVacacion;
use App\Models\Ventas\Plan;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Log;
use Src\Shared\Utils;
use Throwable;

class PlanVacacionController extends Controller
{
    private string $entidad = "Plan de Vacacion";

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {   $results = PlanVacacion::filter()->get();
        $results = PlanVacacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PlanVacacionRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(PlanVacacionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $plan = PlanVacacion::create($datos);
            $modelo = new PlanVacacionResource($plan);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
        } catch (Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param PlanVacacion $plan
     * @return JsonResponse
     */
    public function show(PlanVacacion $plan)
    {
        $modelo = new PlanVacacionResource($plan);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PlanVacacionRequest $request
     * @param PlanVacacion $plan
     * @return JsonResponse
     * @throws Throwable|ValidationException
     */
    public function update(PlanVacacionRequest $request, PlanVacacion $plan)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $plan->update($datos);

            $modelo = new PlanVacacionResource($plan->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th, 'Actualizar ' . $this->entidad);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

}
