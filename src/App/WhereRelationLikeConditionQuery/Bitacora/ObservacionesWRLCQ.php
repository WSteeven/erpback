<?php

// Archivo: Src/App/WhereRelationLikeConditionQuery/Bitacora/ObservacionesWRLCQ.php
namespace Src\App\WhereRelationLikeConditionQuery\Bitacora;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

class ObservacionesWRLCQ extends BaseClause
{
    public function apply($query): Builder
    {
        $valor = $this->values['like'];

        return $query->where(function ($q) use ($valor) {
            $q->where('observaciones', 'like', '%' . $valor . '%')
                ->orWhere('observaciones', 'like', '%' . strtoupper($valor) . '%')
                ->orWhere('observaciones', 'like', '%' . strtolower($valor) . '%');
        });
    }
}
