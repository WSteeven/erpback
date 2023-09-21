<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;

trait ProveedoresFilter
{
    /**
     * This is a sample custom query
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param                                       $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sample_like(Builder $builder, $value)
    {
        return $builder->where('empresa.razon_social', 'like', '%'.$value.'%');
    }
}
