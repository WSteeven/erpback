<?php

namespace Src\App;

// use App\Http\Resources\SubtareaResource;
use App\Http\Resources\TrabajoResource;
use App\Models\Empleado;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Log;

class TrabajoService
{
    /*public function __construct()
    {
    }*/

    public function obtenerFiltradosEstados($estados, $campos)
    {
        $estados = explode(',', $estados);
        $results = Trabajo::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->orderBy('fecha_hora_asignacion', 'asc')->get($campos);
        return TrabajoResource::collection($results);
    }

    public function obtenerTodos()
    {
        // $results = Trabajo::ignoreRequest(['estados'])->filter()->orderBy('fecha_hora_creacion', 'desc')->get();
        //$results = Trabajo::ignoreRequest(['campos'])->filter()->orderBy('fecha_hora_creacion', 'desc')->get();
        $results = Trabajo::filter()->get();
        return TrabajoResource::collection($results);
    }

    // quitar
    public function obtenerFiltradosEstadosCampos($estados, $campos)
    {
        $estados = explode(',', $estados);
        $results = Trabajo::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->get($campos);
        return $results;
    }

    // quitar
    public function obtenerPaginacion($offset)
    {
        $filter = Trabajo::ignoreRequest(['offset'])->filter()->orderBy('fecha_hora_creacion', 'desc')->simplePaginate($offset);
        TrabajoResource::collection($filter);
        return $filter;
    }

    // quitar
    public function obtenerAsignadasPaginacion(Empleado $empleado, $offset)
    {
        $filter = $empleado->subtareas()->ignoreRequest(['offset'])->filter()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        TrabajoResource::collection($filter);
        return $filter;
    }
}
