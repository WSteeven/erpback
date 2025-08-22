<?php

// Archivo: Src/App/WhereRelationLikeCondition/Bitacora/ProtectorWRLC.php
namespace Src\App\WhereRelationLikeCondition\Bitacora;

use Src\App\WhereRelationLikeConditionQuery\Bitacora\ProtectorWRLCQ;

class ProtectorWRLC
{
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['like']) && (
            $field == 'protector.nombres' ||
            $field == 'protector.apellidos'
        )) {
            $method = ProtectorWRLCQ::class;
        }
        return $method ?? null;
    }
}
