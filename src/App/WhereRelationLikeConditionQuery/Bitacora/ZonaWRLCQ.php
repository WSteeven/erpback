<?php

// Archivo: Src/App/WhereRelationLikeConditionQuery/Bitacora/ZonaWRLCQ.php
namespace Src\App\WhereRelationLikeConditionQuery\Bitacora;

use App\Models\Seguridad\Zona;
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

class ZonaWRLCQ extends BaseClause
{
    public function apply($query): Builder
    {
        $valor = $this->values['like'];
        $ids = Zona::where('nombre', 'like', '%' . $valor . '%')->pluck('id');
        return $query->whereIn('zona_id', $ids);
    }
}
