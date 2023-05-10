<?php

namespace App\ModelFilters;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Builder;

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
}
