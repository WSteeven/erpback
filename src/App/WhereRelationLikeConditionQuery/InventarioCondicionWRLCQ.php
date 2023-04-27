<?php

namespace Src\App\WhereRelationLikeConditionQuery;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

class InventarioCondicionWRLCQ extends BaseClause{

    /**
     * This PHP function applies a query to filter results based on a condition of product names.
     * 
     * @param query The query parameter is an instance of the Laravel query builder, which is used to
     * build and execute database queries.
     * 
     * @return Builder A Builder instance is being returned.
     */
    public function apply($query): Builder{
        return $query->whereHas('condiciones_de_productos', function($q){
            $q->where('nombre', 'like', '%'.$this->values['like'].'%');
        });
    }
}