<?php

namespace Src\App\WhereRelationLikeConditionQuery\Subtarea;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class ProyectoWRLCQ extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query->whereHas('tarea', function($query) {
            $query->whereHas('proyecto', function($q) {
                return $q->where('codigo_proyecto', 'like', "%" . $this->values['like'] . "%");
            });
        });
    }
}
