<?php

namespace Src\App\WhereRelationLikeCondition;

use Src\App\WhereRelationLikeConditionQuery\TrabajoTareaWRLCQ;

class TrabajoTareaWRLC
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
        if (!empty($params['like']) && $field == 'tarea.codigo_tarea') {
            $method = TrabajoTareaWRLCQ::class;
        }

        return $method ?? null;
    }
}
