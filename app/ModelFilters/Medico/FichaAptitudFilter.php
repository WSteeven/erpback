<?php

namespace App\ModelFilters\Medico;

use Illuminate\Database\Eloquent\Builder;

trait FichaAptitudFilter
{
    public function paciente_id(Builder $builder, $value)
    {
        return $builder->whereHas('registroEmpleadoExamen', function ($query) use ($value) {
            $query->where('empleado_id', $value);
        });
    }
}
