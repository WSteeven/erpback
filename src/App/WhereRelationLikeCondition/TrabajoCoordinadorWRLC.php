<?php

namespace Src\App\WhereRelationLikeCondition;

use Src\App\WhereRelationLikeConditionQuery\TrabajoCoordinadorWRLCQ;

class TrabajoCoordinadorWRLC
{
    /**
     * @param $field
     * @param $params
     * @param $is_override_method
     *
     * @return string|null
     */
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['like']) && $field == 'tarea.coordinador.nombres') {
            $method = TrabajoCoordinadorWRLCQ::class;
        }

        return $method ?? null;
    }
}
