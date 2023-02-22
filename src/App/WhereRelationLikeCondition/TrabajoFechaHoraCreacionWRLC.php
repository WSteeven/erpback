<?php

namespace Src\App\WhereRelationLikeCondition;

use Src\App\WhereRelationLikeConditionQuery\TrabajoFechaHoraCreacionWRLCQ;

/**
 * Class WhereRelationLikeCondition.
 */
class TrabajoFechaHoraCreacionWRLC //implements DetectorConditionsContract
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
        if (!empty($params['like']) && $field == 'fecha_hora_creacion') {
            $method = TrabajoFechaHoraCreacionWRLCQ::class;
        }

        return $method ?? null;
    }
}
