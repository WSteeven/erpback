<?php

namespace Src\App\WhereRelationLikeCondition;

use Src\App\WhereRelationLikeConditionQuery\TrabajoClienteWRLCQ;

/**
 * Class WhereRelationLikeCondition.
 */
class TrabajoClienteWRLC //implements DetectorConditionsContract
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
        if (!empty($params['like']) && $field == 'cliente.empresa.razon_social') {
            $method = TrabajoClienteWRLCQ::class;
        }

        return $method ?? null;
    }
}
