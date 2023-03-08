<?php

namespace Src\App;

use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\Subtarea;
use Illuminate\Support\Facades\Log;

class TrabajoAsignadoService
{
    public function obtenerTrabajoAsignadoGrupo(Empleado $empleado)
    {
        // $results = Subtarea::filter()->where('fecha_hora_asignacion', '!=', null)->where('grupo_id', $grupo)->get();
        return $empleado->grupo->subtareas()->filter()->where('fecha_hora_asignacion', '!=', null)->get();
        // return SubtareaResource::collection($results);
    }

    public function obtenerTrabajoAsignadoEmpleado(Empleado $empleado)
    {
        // $results = Subtarea::filter()->where('fecha_hora_asignacion', '!=', null)->where('empleado_id', $id_empleado)->get();
        return $empleado->subtareas()->filter()->where('fecha_hora_asignacion', '!=', null)->get();
        // return SubtareaResource::collection($results);
    }
}
