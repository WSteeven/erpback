<?php

namespace Src\App\WhereRelationLikeConditionQuery\Tareas;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class TipoAlimentacionWRLCQ extends BaseClause
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

        return $query->whereHas('tipoAlimentacion', function ($q) use ($operador, $valor) {
            $q->where('descripcion', $operador, '%' . $valor . '%');
        });
    }
}
