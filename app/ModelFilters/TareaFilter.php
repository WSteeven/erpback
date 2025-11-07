<?php

namespace App\ModelFilters;



use Illuminate\Database\Eloquent\Builder;

trait TareaFilter{

    public function filterCustomRaw_data(Builder $builder, $value)
    {

        return $builder->whereRaw("JSON_EXTRACT(raw_data, '$._v.D') = ?", [$value]);
    }
}
