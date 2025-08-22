<?php

// Archivo: App/ModelFilters/BitacoraFilter.php
namespace App\ModelFilters;

use App\Models\Seguridad\Zona;
use Illuminate\Database\Eloquent\Builder;


trait BitacoraFilter
{
    /**
     * Filtrar por zona
     */
    public function zona(Builder $builder, $value)
    {
        $ids = Zona::where('nombre', 'like', "%{$value}%")->pluck('id');
        return $builder->whereIn('zona_id', $ids);
    }

        /**
     * Filtrar por jornada
     */
    public function jornada(Builder $builder, $value)
    {
        return $builder->where('jornada', $value);
    }
}
