<?php

namespace Src\App;

use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\Subtarea;
use Illuminate\Support\Facades\Log;

class TrabajoAsignadoService
{
    /*****************************
     * Trabajo actual y atrasado
     *****************************/
    /* public function obtenerTrabajoAtrasadoAgendadoGrupo(Empleado $empleado)
    {
        return $empleado->grupo->subtareas()->filter()->where('fecha_hora_agendado', '!=', null)->anterioresNoFinalizados()->noEsStandby()->get();
    } */

    public function obtenerTrabajoAsignadoEmpleado(Empleado $empleado)
    {
        return $empleado->subtareas()->filter()->where('fecha_hora_agendado', '!=', null)->anterioresNoFinalizados()->noEsStandby()->get();
    }

    public function obtenerTodosTrabajosAsignadosEmpleado(Empleado $empleado)
    {
        return $empleado->subtareas()->where('fecha_hora_agendado', '!=', null)->whereIn('estado', [Subtarea::AGENDADO, Subtarea::EJECUTANDO, Subtarea::PAUSADO, Subtarea::REALIZADO])->noEsStandby()->get();
    }

    /********************
     * Proximos trabajos
     ********************/
    /* public function obtenerFuturoTrabajoAsignadoGrupo(Empleado $empleado)
    {
        return $empleado->grupo->subtareas()->where('fecha_hora_agendado', '!=', null)->fechaFuturo()->noEsStandby()->get();
    } */

    public function obtenerFuturoTrabajoAsignadoEmpleado(Empleado $empleado)
    {
        return $empleado->subtareas()->where('fecha_hora_agendado', '!=', null)->fechaFuturo()->noEsStandby()->get();
    }
}
