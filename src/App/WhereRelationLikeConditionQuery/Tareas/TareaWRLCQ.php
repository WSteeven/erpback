<?php

namespace Src\App\WhereRelationLikeConditionQuery\Tareas;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class TareaWRLCQ extends BaseClause
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

        return $query->whereHas('tarea', function ($q) use ($operador, $valor) {
            $q->where('codigo_tarea', $operador, '%' . $valor . '%');
        });
    }
}
