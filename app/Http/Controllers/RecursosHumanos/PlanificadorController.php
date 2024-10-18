<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\PlanificadorRequest;
use App\Http\Resources\RecursosHumanos\PlanificadorResource;
use App\Models\RecursosHumanos\Planificador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class PlanificadorController extends Controller
{
    private string $entidad= 'Planificador';
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Planificador::all();
        $results = PlanificadorResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $datos= $request->validated();
        $modelo = $datos;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Planificador $plan
     * @return JsonResponse
     */
    public function show(Planificador $plan)
    {
        $modelo = new PlanificadorResource($plan);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PlanificadorRequest $request
     * @param Planificador $plan
     * @return JsonResponse
     */
    public function update(PlanificadorRequest $request, Planificador $plan)
    {
        $plan->update($request->validated());
        $modelo = new PlanificadorResource($plan);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Planificador $plan
     * @return JsonResponse
     */
    public function destroy(Planificador $plan)
    {
        $plan->delete();
        $mensaje= Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
