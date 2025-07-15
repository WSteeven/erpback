<?php

// Archivo: Src/App/WhereRelationLikeCondition/Bitacora/AgenteTurnoWRLC.php
namespace Src\App\WhereRelationLikeCondition\Bitacora;

use Src\App\WhereRelationLikeConditionQuery\Bitacora\AgenteTurnoWRLCQ;

class AgenteTurnoWRLC
{
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['like']) && (
            $field == 'agenteTurno.nombres' ||
            $field == 'agenteTurno.apellidos'
        )) {
            $method = AgenteTurnoWRLCQ::class;
        }
        return $method ?? null;
    }
}
