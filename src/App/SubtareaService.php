<?php

namespace Src\App;

use App\Http\Resources\SubtareaResource;
use App\Models\Subtarea;

class SubtareaService
{
    public function __construct()
    {
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
        $results = Subtarea::filter()->get();
        return SubtareaResource::collection($results);
    }

    public function obtenerAsignadasTodos()
    {
        $results = Subtarea::filter()->where('fecha_hora_asignacion', '!=', null)->get();
        return SubtareaResource::collection($results);
    }
}
