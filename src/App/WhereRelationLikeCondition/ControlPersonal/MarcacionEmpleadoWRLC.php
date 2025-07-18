<?php

namespace Src\App\WhereRelationLikeCondition\ControlPersonal;

use Src\App\WhereRelationLikeConditionQuery\ControlPersonal\MarcacionEmpleadoWRLCQ;

class MarcacionEmpleadoWRLC
{

    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['like']) && ($field == 'empleado.nombres' || $field == 'empleado.apellidos')) {
            $method = MarcacionEmpleadoWRLCQ::class;
        }
        return $method ?? null;
    }
}
