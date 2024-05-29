<?php

namespace App\ModelFilters\Medico;

use Illuminate\Database\Eloquent\Builder;

trait ConsultaMedicaFilter
{
    public function empleado_id(Builder $builder, $value)
    {
        return $builder->whereHas('registroEmpleadoExamen', function ($query) use ($value) {
            $query->where('empleado_id', $value);
        })->orWhereHas('citaMedica', function ($query) use ($value) {
            $query->where('paciente_id', $value);
        });
    }
}
