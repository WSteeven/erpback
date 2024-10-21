<?php

namespace Src\App\WhereRelationLikeCondition\Tareas;

use Src\App\WhereRelationLikeConditionQuery\Tareas\TipoAlimentacionWRLCQ;

class TipoAlimentacionWRLC
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
        if (!empty($params['like']) && $field == 'tipo_alimentacion.descripcion') {
            $method = TipoAlimentacionWRLCQ::class;
        }

        return $method ?? null;
    }
}
