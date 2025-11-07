<?php

namespace App\ModelFilters;

trait GrupoFilter{

    public function filterCustomNombre_alternativo($builder, $value)
    {

        return $builder->whereNotNull($value);
    }
}
