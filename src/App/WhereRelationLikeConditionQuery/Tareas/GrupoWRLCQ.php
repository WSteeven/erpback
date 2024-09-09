<?php

namespace Src\App\WhereRelationLikeConditionQuery\Tareas;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class GrupoWRLCQ extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        $valor = $this->values['like'];
        $operador = 'like';

        return $query->whereHas('grupo', function ($q) use ($operador, $valor) {
            $q->where('nombre', $operador, $valor);
        });
    }
}
