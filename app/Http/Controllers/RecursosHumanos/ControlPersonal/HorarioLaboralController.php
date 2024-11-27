<?php

namespace App\Http\Controllers\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ControlPersonal\HorarioLaboralRequest;
use App\Http\Resources\HorarioLaboralResource;
use App\Models\RecursosHumanos\ControlPersonal\HorarioLaboral;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class HorarioLaboralController extends Controller
{
    private $entidad = 'Horario Laboral';

    public function __construct()
    {
        $this->middleware('can:ver.horario_laboral')->only('index', 'show');
        $this->middleware('can:crear.horario_laboral')->only('store');
        $this->middleware('can:editar.horario_laboral')->only('update');
        $this->middleware('can:eliminar.horario_laboral')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = HorarioLaboral::filter()->orderBy('hora_entrada', 'asc')->get();
        $results = HorarioLaboralResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HorarioLaboralRequest $request)
    {
        // Respuesta
        $modelo = HorarioLaboral::create($request->validated());
        $modelo = new HorarioLaboralResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HorarioLaboral  $horario
     * @return \Illuminate\Http\Response
     */
    public function show(HorarioLaboral $horario)
    {
        $modelo = new HorarioLaboralResource($horario);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HorarioLaboral  $horario
     * @return \Illuminate\Http\Response
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
     * @param  \App\Models\HorarioLaboral  $horario
     * @return \Illuminate\Http\Response
     */
    public function destroy(HorarioLaboral $horario)
    {
        $horario->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
