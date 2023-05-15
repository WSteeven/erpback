<?php

namespace Src\App\WhereRelationLikeConditionQuery\Subtarea;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class CantidadAdjuntosWRLCQ extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        $valor = $this->values['value'];
        $operador = $this->values['operator'];

        return $query->join('archivos_subtareas', 'subtareas.id', '=', 'archivos_subtareas.subtarea_id')
            ->groupBy('codigo_subtarea')
            ->havingRaw('COUNT(archivos_subtareas.nombre) ' . $operador . ' ?', [$valor])
            ->select('subtareas.*');
    }
}
