<?php

namespace Src\App\WhereRelationLikeCondition;

use Src\App\WhereRelationLikeConditionQuery\TrabajoTipoTrabajoWRLCQ;

/**
 * Class WhereRelationLikeCondition.
 */
class TrabajoTipoTrabajoWRLC
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
        if (!empty($params['like']) && $field == 'tipo_trabajo.descripcion') {
            $method = TrabajoTipoTrabajoWRLCQ::class;
        }

        return $method ?? null;
    }
}
