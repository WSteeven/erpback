<?php

namespace Src\App\WhereRelationLikeCondition;

use Src\App\WhereRelationLikeConditionQuery\TrabajoFechaHoraEjecucionWRLCQ;
use Src\App\WhereRelationLikeConditionQuery\TrabajoFechaHoraFinalizacionWRLCQ;

/**
 * Class WhereRelationLikeCondition.
 */
class TrabajoFechaHoraFinalizacionWRLC //implements DetectorConditionsContract
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
        if (!empty($params['value']) && $field == 'fecha_hora_finalizacion') {
            $method = TrabajoFechaHoraFinalizacionWRLCQ::class;
        }

        return $method ?? null;
    }
}
