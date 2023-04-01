<?php

namespace Src\App;

use App\Http\Requests\SubtareaRequest;
use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\MovilizacionSubtarea;
use App\Models\Subtarea;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubtareaService
{
    public function __construct()
    {
    }

    public function guardarSubtarea(SubtareaRequest $request)
    {
        $tarea_id = $request['tarea'];

        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['codigo_subtarea'] = Tarea::find($tarea_id)->codigo_tarea . '-' . (Subtarea::where('tarea_id', $tarea_id)->count() + 1);
        $datos['subtarea_dependiente_id'] = $request->safe()->only(['subtarea_dependiente'])['subtarea_dependiente'];
        // $modo_asignacion_trabajo = $request->safe()->only(['modo_asignacion_trabajo'])['modo_asignacion_trabajo'];
        $datos['tipo_trabajo_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['empleado_id'] = $request->safe()->only(['empleado'])['empleado'];
        $datos['fecha_hora_creacion'] = Carbon::now();

        // Calcular estados
        $datos['estado'] = Subtarea::CREADO;

        return Subtarea::create($datos);
    }

    public function obtenerFiltradosEstadosCampos($estados, $campos)
    {
        $estados = explode(',', $estados);
        $results = Subtarea::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->get($campos);
        // Log::channel('testing')->info('Log', ['subtareas filtradas por estado', $results]);
        return $results;
    }

    public function obtenerFiltradosEstados($estados)
    {
        $estados = explode(',', $estados);
        $results = Subtarea::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->orderBy('fecha_hora_asignacion', 'asc')->get();
        return $results;
    }

    public function obtenerPaginacion($offset)
    {
        $filter = Subtarea::ignoreRequest(['offset'])->filter()->orderBy('fecha_hora_creacion', 'desc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }

    public function obtenerAsignadasPaginacion(Empleado $empleado, $offset)
    {
        $filter = $empleado->subtareas()->ignoreRequest(['offset'])->filter()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }

    /*public function obtenerTodos()
    {
        $results = Subtarea::ignoreRequest(['estados'])->filter()->orderBy('fecha_hora_creacion', 'desc')->get();
        return SubtareaResource::collection($results);
    }*/

    public function obtenerTodos()
    {
        $results = Subtarea::ignoreRequest(['campos'])->filter()->get();
        return SubtareaResource::collection($results);
    }

    public function marcarTiempoLlegadaMovilizacion(Subtarea $subtarea)
    {
        $movilizacion = MovilizacionSubtarea::where('subtarea_id', $subtarea->id)->where('empleado_id', Auth::user()->empleado->id)->where('fecha_hora_llegada', null)->orderBy('fecha_hora_salida', 'desc')->first();

        if ($movilizacion) {
            $movilizacion->fecha_hora_llegada = Carbon::now();
            $movilizacion->save();
        }
    }
}
