<?php

// Archivo: Src/App/WhereRelationLikeConditionQuery/Bitacora/ConductorWRLCQ.php
namespace Src\App\WhereRelationLikeConditionQuery\Bitacora;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

class ConductorWRLCQ extends BaseClause
{
    public function apply($query): Builder
    {
        $valor = $this->values['like'];
        $ids = Empleado::where('nombres', 'like', '%' . $valor . '%')
                      ->orWhere('apellidos', 'like', '%' . $valor . '%')
                      ->pluck('id');
        return $query->whereIn('conductor_id', $ids);
    }
}
