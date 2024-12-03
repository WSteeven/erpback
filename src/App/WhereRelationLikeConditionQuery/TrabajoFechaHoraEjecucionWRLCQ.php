<?php

namespace Src\App\WhereRelationLikeConditionQuery;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class TrabajoFechaHoraEjecucionWRLCQ extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
//        return $query->where('fecha_hora_ejecucion', 'like', "%" . $this->values['like'] . "%");
        return $query->where('fecha_hora_ejecucion', $this->values['operator'], $this->values['value']);
    }
}
