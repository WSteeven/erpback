<?php

namespace Src\App\WhereRelationLikeCondition;

use Src\App\WhereRelationLikeConditionQuery\TrabajoFechaHoraEjecucionWRLCQ;

/**
 * Class WhereRelationLikeCondition.
 */
class TrabajoFechaHoraEjecucionWRLC //implements DetectorConditionsContract
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
        if (!empty($params['value']) && $field == 'fecha_hora_ejecucion') {
            $method = TrabajoFechaHoraEjecucionWRLCQ::class;
        }

        return $method ?? null;
    }
}
