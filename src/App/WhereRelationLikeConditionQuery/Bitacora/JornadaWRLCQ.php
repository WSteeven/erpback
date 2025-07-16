<?php

namespace Src\App\WhereRelationLikeConditionQuery\Bitacora;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

class JornadaWRLCQ extends BaseClause
{
    public function apply($query): Builder
    {
        $valor = $this->values['like'];

        return $query->where(function ($q) use ($valor) {
            $q->where('jornada', 'like', '%' . $valor . '%')
                ->orWhere('jornada', 'like', '%' . strtoupper($valor) . '%')
                ->orWhere('jornada', 'like', '%' . strtolower($valor) . '%');
        });
    }
}
