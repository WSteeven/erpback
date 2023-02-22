<?php

namespace Src\App\WhereRelationLikeConditionQuery;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class TrabajoCoordinadorWRLCQ extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query->whereHas('coordinador', function ($q) {
            $q->where('nombres', 'like', "%" . $this->values['like'] . "%")->orWhere('apellidos', 'like', "%" . $this->values['like'] . "%");
        });
    }
}
