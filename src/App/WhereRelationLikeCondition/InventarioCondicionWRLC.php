<?php

namespace Src\App\WhereRelationLikeCondition;

use Src\App\WhereRelationLikeConditionQuery\InventarioCondicionWRLCQ;

class InventarioCondicionWRLC
{
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['like']) && $field == 'condicion.nombre') {
            $method = InventarioCondicionWRLCQ::class;
        }

        return $method ?? null;
    }
}
