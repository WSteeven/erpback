<?php

namespace Src\App\WhereRelationLikeCondition\Subtarea;

use Src\App\WhereRelationLikeConditionQuery\CantidadAdjuntosWRLCQ;

class CantidadAdjuntosWRLC
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
        if ($field == 'cantidad_adjuntos') {
            $method = CantidadAdjuntosWRLCQ::class;
        }

        return $method ?? null;
    }
}
