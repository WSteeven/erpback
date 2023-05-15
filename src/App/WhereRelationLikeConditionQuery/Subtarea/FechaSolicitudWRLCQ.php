<?php

namespace Src\App\WhereRelationLikeConditionQuery\Subtarea;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class FechaSolicitudWRLCQ extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        Log::channel('testing')->info('Log', ['Coordinador: ', $this->values]);

        $valor = $this->values['value'];
        $operador = $this->values['operator'];

        return $query->join('tareas', 'subtareas.tarea_id', '=', 'tareas.id')
            ->where('tareas.fecha_solicitud', $operador, $valor)
            ->select('subtareas.*');
    }
}
