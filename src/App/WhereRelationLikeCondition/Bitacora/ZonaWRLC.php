<?php

// Archivo: Src/App/WhereRelationLikeCondition/Bitacora/ZonaWRLC.php
namespace Src\App\WhereRelationLikeCondition\Bitacora;

use Src\App\WhereRelationLikeConditionQuery\Bitacora\ZonaWRLCQ;

class ZonaWRLC
{
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['like']) && $field == 'zona.nombre') {
            $method = ZonaWRLCQ::class;
        }
        return $method ?? null;
    }
}
