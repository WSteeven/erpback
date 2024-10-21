<?php

namespace Src\App\WhereRelationLikeCondition\Tareas;

use Src\App\WhereRelationLikeConditionQuery\Tareas\GrupoWRLCQ;

class GrupoWRLC
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
        if (!empty($params['like']) && $field == 'grupo.nombre') {
            $method = GrupoWRLCQ::class;
        }

        return $method ?? null;
    }
}
