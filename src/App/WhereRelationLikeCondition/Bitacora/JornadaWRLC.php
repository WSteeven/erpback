<?php

namespace Src\App\WhereRelationLikeCondition\Bitacora;

use Src\App\WhereRelationLikeConditionQuery\Bitacora\JornadaWRLCQ;

class JornadaWRLC
{
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['like']) && $field == 'jornada') {
            $method = JornadaWRLCQ::class;
        }
        return $method ?? null;
    }
}
