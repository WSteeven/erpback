<?php

namespace App\Http\Controllers\RecursosHumanos\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\ControlPersonal\HorarioLaboralRequest;
use App\Http\Resources\RecursosHumanos\ControlPersonal\HorarioLaboralResource;
use App\Models\RecursosHumanos\ControlPersonal\HorarioLaboral;
use Illuminate\Http\JsonResponse;
use Log;
use Src\Shared\Utils;

class HorarioLaboralController extends Controller
{
    private string $entidad = 'Horario Laboral';

    public function __construct()
    {
        $this->middleware('can:puede.ver.horario_laboral')->only('index', 'show');
        $this->middleware('can:puede.crear.horario_laboral')->only('store');
        $this->middleware('can:puede.editar.horario_laboral')->only('update');
        $this->middleware('can:puede.eliminar.horario_laboral')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = HorarioLaboral::filter()->orderBy('nombre')->get();
        $results = HorarioLaboralResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HorarioLaboralRequest $request
     * @return JsonResponse
     */
    public function store(HorarioLaboralRequest $request)
    {
        Log::channel('testing')->info('Log', ['Request', $request]);
        // Respuesta
        $datos = $request->validated();
        Log::channel('testing')->info('Log', ['Datos en el store', $datos]);
        $modelo = HorarioLaboral::create($datos);
        $modelo = new HorarioLaboralResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param HorarioLaboral $horario
     * @return JsonResponse
     */
    public function show(HorarioLaboral $horario)
    {
        $modelo = new HorarioLaboralResource($horario);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HorarioLaboralRequest $request
     * @param HorarioLaboral $horario
     * @return JsonResponse
     */
    public function update(HorarioLaboralRequest $request, HorarioLaboral $horario)
    {
        // Respuesta
        $horario->update($request->validated());
        $modelo = new HorarioLaboralResource($horario->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HorarioLaboral $horario
     * @return JsonResponse
     */
    public function destroy(HorarioLaboral $horario)
    {
        $horario->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
