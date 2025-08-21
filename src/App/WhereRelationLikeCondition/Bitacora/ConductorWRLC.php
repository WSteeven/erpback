<?php

// Archivo: Src/App/WhereRelationLikeCondition/Bitacora/ConductorWRLC.php
namespace Src\App\WhereRelationLikeCondition\Bitacora;

use Src\App\WhereRelationLikeConditionQuery\Bitacora\ConductorWRLCQ;

class ConductorWRLC
{
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['like']) && (
            $field == 'conductor.nombres' ||
            $field == 'conductor.apellidos'
        )) {
            $method = ConductorWRLCQ::class;
        }
        return $method ?? null;
    }
}
