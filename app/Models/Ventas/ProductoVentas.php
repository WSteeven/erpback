<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ProductoVentas extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_productos_ventas';
    protected $fillable =['bundle_id','precio','plan_id'];
    private static $whiteListFilter = [
        '*',
    ];
    public function plan(){
        return $this->hasOne(Planes::class,'id','plan_id');

    }
}
