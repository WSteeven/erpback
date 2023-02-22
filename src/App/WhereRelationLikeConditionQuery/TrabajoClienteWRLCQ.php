<?php

namespace Src\App\WhereRelationLikeConditionQuery;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class TrabajoClienteWRLCQ extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query->whereHas('cliente', function ($q) {
            return $q->whereHas('empresa', function ($query) {
                return $query->where('razon_social', 'like', "" . $this->values['like'] . "");
            });
        });
    }
}
