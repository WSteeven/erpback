<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Ventas  extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_ventas';
    protected $fillable =['orden_id','orden_interna','vendedor_id','producto_id','fecha_activ','estado_activ','forma_pago','comision_id','chargeback','comision_vendedor'];
    private static $whiteListFilter = [
        '*',
    ];
    public function vendedor(){
        return $this->hasOne(Vendedor::class,'id','vendedor_id')->with('empleado');
    }
    public function producto(){
        return $this->hasOne(ProductoVentas::class,'id','producto_id');
    }
    public function comision(){
        return $this->hasOne(Comisiones::class,'id','comision_id');
    }
}
