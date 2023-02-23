<?php

namespace Src\App\WhereRelationLikeConditionQuery;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

class TrabajoCantonWRLCQ extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query->whereHas('proyecto', function ($q) {
            $q->whereHas('canton', function ($q) {
                $q->where('canton', 'like', "%" . $this->values['like'] . "%");
            });
        }); /*->orWhereHas('clienteFinal', function ($q) {
            $q->whereHas('canton', function ($q) {
                $q->where('canton', 'like', "%" . $this->values['like'] . "%");
            });
        });*/
    }
}
