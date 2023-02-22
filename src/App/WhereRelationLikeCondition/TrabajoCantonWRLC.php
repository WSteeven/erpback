<?php

namespace Src\App\WhereRelationLikeCondition;

use Src\App\WhereRelationLikeConditionQuery\TrabajoCantonWRLCQ;

class TrabajoCantonWRLC
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
        if (!empty($params['like']) && $field == 'canton') {
            $method = TrabajoCantonWRLCQ::class;
        }

        return $method ?? null;
    }
}
