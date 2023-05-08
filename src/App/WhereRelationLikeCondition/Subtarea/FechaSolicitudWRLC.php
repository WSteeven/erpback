<?php

namespace Src\App\WhereRelationLikeCondition\Subtarea;

use Src\App\WhereRelationLikeConditionQuery\Subtarea\FechaSolicitudWRLCQ;

class FechaSolicitudWRLC
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
        if ($field == 'fecha_solicitud') {
            $method = FechaSolicitudWRLCQ::class;
        }

        return $method ?? null;
    }
}
