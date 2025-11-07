<?php

namespace App\ModelFilters\Conecel\GestionTareas;



use Illuminate\Database\Eloquent\Builder;

trait TareaFilter{

    public function fecha_real(Builder $builder, $value)
    {

        return $builder->whereRaw("JSON_EXTRACT(raw_data, '$._v.D') = ?", [$value]);
    }
}
