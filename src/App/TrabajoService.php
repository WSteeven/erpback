<?php

namespace Src\App;

use App\Http\Resources\SubtareaResource;
use App\Http\Resources\TrabajoResource;
use App\Models\Empleado;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Log;

class TrabajoService
{
    public function __construct()
    {
    }

    public function obtenerFiltradosEstadosCampos($estados, $campos)
    {
        $estados = explode(',', $estados);
        $results = Trabajo::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->get($campos);
        return $results;
    }

    public function obtenerFiltradosEstados($estados)
    {
        $estados = explode(',', $estados);
        $results = Trabajo::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->orderBy('fecha_hora_asignacion', 'asc')->get();
        return $results;
    }

    public function obtenerPaginacion($offset)
    {
        $filter = Trabajo::ignoreRequest(['offset'])->filter()->orderBy('fecha_hora_creacion', 'desc')->simplePaginate($offset);
        TrabajoResource::collection($filter);
        return $filter;
    }

    public function obtenerAsignadasPaginacion(Empleado $empleado, $offset)
    {
        $filter = $empleado->subtareas()->ignoreRequest(['offset'])->filter()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        TrabajoResource::collection($filter);
        return $filter;
    }

    public function obtenerTodos()
    {
        $results = Trabajo::ignoreRequest(['estados'])->filter()->orderBy('fecha_hora_creacion', 'desc')->get();
        return TrabajoResource::collection($results);
    }
}
