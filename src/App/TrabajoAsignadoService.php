<?php

namespace Src\App;

use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\Subtarea;
use Illuminate\Support\Facades\Log;

class TrabajoAsignadoService
{
    /*****************************
     * Trabajo para el dia actual
     *****************************/
    public function obtenerTrabajoAsignadoGrupo(Empleado $empleado)
    {
        return $empleado->grupo->subtareas()->filter()->where('fecha_hora_agendado', '!=', null)->noEstaRealizado()->fechaActual()->get();
    }

    public function obtenerTrabajoAsignadoEmpleado(Empleado $empleado)
    {
        return $empleado->subtareas()->filter()->where('fecha_hora_agendado', '!=', null)->anterioresNoFinalizados()->get();
    }

    /*****************************
     * Trabajo atrasado
     *****************************/
    public function obtenerTrabajoAtrasadoAgendadoGrupo(Empleado $empleado)
    {
        return $empleado->grupo->subtareas()->filter()->where('fecha_hora_agendado', '!=', null)->anterioresNoFinalizados()->get();
    }

    /********************
     * Proximos trabajos
     ********************/
    public function obtenerFuturoTrabajoAsignadoGrupo(Empleado $empleado)
    {
        return $empleado->grupo->subtareas()->where('fecha_hora_agendado', '!=', null)->fechaFuturo()->get();
    }

    public function obtenerFuturoTrabajoAsignadoEmpleado(Empleado $empleado)
    {
        return $empleado->subtareas()->filter()->where('fecha_hora_agendado', '!=', null)->fechaFuturo()->get();
    }
}
