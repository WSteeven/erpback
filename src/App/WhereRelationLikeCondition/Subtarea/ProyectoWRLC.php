<?php

namespace Src\App\WhereRelationLikeCondition\Subtarea;

use Src\App\WhereRelationLikeConditionQuery\Subtarea\ProyectoWRLCQ;

/**
 * Class WhereRelationLikeCondition.
 */
class ProyectoWRLC //implements DetectorConditionsContract
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
        if (!empty($params['like']) && $field == 'proyecto.codigo_proyecto') {
            $method = ProyectoWRLCQ::class;
        }

        return $method ?? null;
    }
}
