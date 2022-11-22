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
        $filter = Subtarea::ignoreRequest(['offset'])->filter()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }

    public function obtenerTodos()
    {
        $results = Subtarea::filter()->get();
        return SubtareaResource::collection($results);
    }
}
