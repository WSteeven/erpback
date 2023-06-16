<?php

namespace App\ModelFilters;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

trait SubtareasFilter
{
    /**
     * This is a sample custom query
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param                                       $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function titulo(Builder $builder, $value)
    {
        return $builder->where('subtareas.titulo', 'like', '%' . $value . '%');
    }

    public function coordinador(Builder $builder, $value)
    {
        $valor = '%' . $value . '%';
        $ids = Empleado::where('nombres', 'like', $valor)->orWhere('apellidos', 'like', $valor)->pluck('id');

        return $builder->join('tareas', 'subtareas.tarea_id', '=', 'tareas.id')->select('subtareas.*')->whereIn('tareas.coordinador_id', $ids);
    }

    public function codigo_tarea(Builder $builder, $value)
    {
        return $builder->whereHas('tarea', function ($q) use ($value) {
            $q->where('codigo_tarea', 'like', "%" . $value . "%");
        });
    }

    public function proyecto(Builder $builder, $value)
    {
        return $builder->whereHas('tarea', function ($query) use ($value) {
            $query->whereHas('proyecto', function ($q) use ($value) {
                return $q->where('codigo_proyecto', 'like', "%" . $value . "%");
            });
        });
    }

    public function grupo(Builder $builder, $value)
    {
        // Log::channel('testing')->info('Log', ['Coordinador: ', 'Dentro de grupo ...']);
        return $builder->whereHas('grupoResponsable', function ($query) use ($value) {
            $query->where('nombre', 'like', '%' . $value . '%');
        });
    }

    public function empleado(Builder $builder, $value)
    {
        // Log::channel('testing')->info('Log', ['Coordinador: ', 'Dentro de grupo ...']);
        return $builder->whereHas('empleadoResponsable', function ($query) use ($value) {
            $query->where('nombres', 'like', '%' . $value . '%')->orWhere('apellidos', 'like', '%' . $value . '%');
        });
    }
}
