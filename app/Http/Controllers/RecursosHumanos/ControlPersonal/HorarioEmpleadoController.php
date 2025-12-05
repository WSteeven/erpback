<?php

namespace App\Http\Controllers\RecursosHumanos\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\ControlPersonal\HorarioEmpleadoRequest;
use App\Http\Resources\RecursosHumanos\ControlPersonal\HorarioEmpleadoResource;
use App\Models\RecursosHumanos\ControlPersonal\HorarioEmpleado;
use Illuminate\Http\JsonResponse;
use Src\Shared\Utils;

class HorarioEmpleadoController extends Controller
{
    private string $entidad = 'Horario Empleado';
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = HorarioEmpleado::filter()->orderBy('empleado_id')->get();
        $results = HorarioEmpleadoResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HorarioEmpleadoRequest $request
     * @return JsonResponse
     */
    public function store(HorarioEmpleadoRequest $request)
    {

        // Respuesta
        $datos = $request->validated();
        $modelo = HorarioEmpleado::create($datos);
        $modelo = new HorarioEmpleadoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));

    }

    /**
     * Display the specified resource.
     *
     * @param HorarioEmpleado $horario
     * @return JsonResponse
     */
    public function show(HorarioEmpleado $horario)
    {
        $modelo = new HorarioEmpleadoResource($horario);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HorarioEmpleadoRequest $request
     * @param HorarioEmpleado $horario
     * @return JsonResponse
     */
    public function update(HorarioEmpleadoRequest $request, HorarioEmpleado $horario)
    {
        // Respuesta
        $horario->update($request->validated());
        $modelo = new HorarioEmpleadoResource($horario->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HorarioEmpleado $horario
     * @return JsonResponse
     */
    public function destroy(HorarioEmpleado $horario)
    {
        $horario->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
