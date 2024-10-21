<?php

namespace Src\App\WhereRelationLikeCondition\Tareas;

use Src\App\WhereRelationLikeConditionQuery\Tareas\CoordinadorWRLCQ;
use Src\App\WhereRelationLikeConditionQuery\Tareas\TareaWRLCQ;

class CoordinadorWRLC
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
        if (!empty($params['like']) && $field == 'coordinador.nombres_apellidos') {
            $method = CoordinadorWRLCQ::class;
        }

        return $method ?? null;
    }
}
