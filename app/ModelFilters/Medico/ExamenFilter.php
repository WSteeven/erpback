<?php

namespace App\ModelFilters\Medico;

use Illuminate\Database\Eloquent\Builder;

trait ExamenFilter
{
    public function empleado_id(Builder $builder, $value)
    {
        return $builder->whereHas('estadoSolicitudExamen', function ($query) use ($value) {
            $query->whereHas('solicitudExamen', function ($query) use ($value) {
                $query->whereHas('registroEmpleadoExamen', function ($query) use ($value) {
                    $query->where('empleado_id', $value);
                });
            });
        });
    }

    public function registro_empleado_examen_id(Builder $builder, $value)
    {
        return $builder->whereHas('estadoSolicitudExamen', function ($query) use ($value) {
            $query->whereHas('solicitudExamen', function ($query) use ($value) {
                $query->whereHas('registroEmpleadoExamen', function ($query) use ($value) {
                    $query->where('id', $value);
                });
            });
        });
    }
}
