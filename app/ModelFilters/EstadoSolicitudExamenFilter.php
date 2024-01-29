<?php

namespace App\ModelFilters;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

trait EstadoSolicitudExamenFilter
{
    public function empleado_id(Builder $builder, $value)
    {
        return $builder->whereHas('registroEmpleadoExamen', function ($query) use ($value) {
            $query->where('empleado_id', $value);
        });
    }
}
