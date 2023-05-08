<?php

namespace Src\App\WhereRelationLikeCondition\Subtarea;

use Src\App\WhereRelationLikeConditionQuery\Subtarea\CodigoTareaWRLCQ;

class CodigoTareaWRLC
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
            $method = CodigoTareaWRLCQ::class;
        }

        return $method ?? null;
    }
}
