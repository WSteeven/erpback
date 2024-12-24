<?php

namespace Src\App\WhereRelationLikeConditionQuery;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

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
        $valor = $this->values['like'];
        $ids = Empleado::where('nombres', 'like', '%' . $valor . '%')->orWhere('apellidos', 'like', '%' . $valor . '%')->pluck('id');
        //Log::channel('testing')->info('Log', ['Valor', $valor]);
        //Log::channel('testing')->info('Log', ['IDs', $ids]);

        return $query->join('tareas', 'subtareas.tarea_id', '=', 'tareas.id')->select('subtareas.*')->whereIn('tareas.coordinador_id', $ids);

        /* return $query->tarea()->whereHas('coordinador', function ($q) {
            $q->where('nombres', 'like', "%" . $this->values['like'] . "%")->orWhere('apellidos', 'like', "%" . $this->values['like'] . "%");
        }); */
    }
}
