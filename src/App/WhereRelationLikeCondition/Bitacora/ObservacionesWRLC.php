<?php

// Archivo: Src/App/WhereRelationLikeCondition/Bitacora/ObservacionesWRLC.php
namespace Src\App\WhereRelationLikeCondition\Bitacora;

use Src\App\WhereRelationLikeConditionQuery\Bitacora\ObservacionesWRLCQ;

class ObservacionesWRLC
{
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['like']) && $field == 'observaciones') {
            $method = ObservacionesWRLCQ::class;
        }
        return $method ?? null;
    }
}
