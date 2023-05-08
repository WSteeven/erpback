<?php

namespace Src\App\WhereRelationLikeConditionQuery\Subtarea;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class CodigoTareaWRLCQ extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query->whereHas('tarea', function ($q) {
            $q->where('codigo_tarea', 'like', "%" . $this->values['like'] . "%");
        });
    }
}
