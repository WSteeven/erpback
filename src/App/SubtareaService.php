<?php

namespace Src\App;

use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\Subtarea;
use Illuminate\Support\Facades\Log;

class SubtareaService
{
    public function __construct()
    {
    }

    public function obtenerFiltradosEstadosCampos($estados, $campos)
    {
        $estados = explode(',', $estados);
        $results = Subtarea::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->get($campos);
        Log::channel('testing')->info('Log', ['subtareas filtradas por estado', $results]);
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
        $filter = Subtarea::ignoreRequest(['offset'])->filter()->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }

    public function obtenerAsignadasPaginacion(Empleado $empleado, $offset)
    {
        $filter = $empleado->subtareas()->ignoreRequest(['offset'])->filter()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }

    public function obtenerTodos()
    {
        $results = Subtarea::ignoreRequest(['estados'])->filter()->get();
        return SubtareaResource::collection($results);
    }

    public function obtenerAsignadasTodos()
    {
        $results = Subtarea::filter()->where('fecha_hora_asignacion', '!=', null)->get();
        return SubtareaResource::collection($results);
    }
}
