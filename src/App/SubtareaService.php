<?php

namespace Src\App;

use App\Http\Resources\SubtareaResource;
use App\Models\Subtarea;
use Illuminate\Support\Facades\Log;

class SubtareaService
{
    public function __construct()
    {
    }

    public function obtenerFiltradosEstadosCampos($estados,$campos)
    {
        // Log::channel('testing')->info('Log', ['estados recibidos', $estados]);
        $estados = explode(',', $estados);
        // Log::channel('testing')->info('Log', ['estados en array', $estados]);
        $results = Subtarea::ignoreRequest(['estados','campos'])->filter()->whereIn('estado', $estados)->get($campos);
        Log::channel('testing')->info('Log', ['subtareas filtradas por estado', $results]);
        // SubtareaResource::collection($results);
        return $results;
    }
    public function obtenerFiltradosEstados($estados)
    {
        // Log::channel('testing')->info('Log', ['estados recibidos', $estados]);
        $estados = explode(',', $estados);
        // Log::channel('testing')->info('Log', ['estados en array', $estados]);
        $results = Subtarea::ignoreRequest(['estados','campos'])->filter()->whereIn('estado', $estados)->get();
        Log::channel('testing')->info('Log', ['subtareas filtradas por estado', $results]);
        // SubtareaResource::collection($results);
        return $results;
    }

    public function obtenerPaginacion($offset)
    {
        //$filter = Subtarea::ignoreRequest(['offset'])->filter()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        $filter = Subtarea::ignoreRequest(['offset'])->filter()->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }

    public function obtenerAsignadasPaginacion($offset)
    {
        $filter = Subtarea::ignoreRequest(['offset'])->filter()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
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
