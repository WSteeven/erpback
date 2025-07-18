<?php

namespace Src\App\WhereRelationLikeConditionQuery\ControlPersonal;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

class MarcacionEmpleadoWRLCQ extends BaseClause
{
    public function apply($query): Builder
    {
        $valor = $this->values['like'];
        $ids = Empleado::where('nombres', 'like', '%' . $valor . '%')->orWhere('apellidos', 'like', '%' . $valor . '%')->pluck('id');
        return $query->whereIn('empleado_id', $ids);
    }
}
