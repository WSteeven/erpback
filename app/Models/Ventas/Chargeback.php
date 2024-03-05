<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Chargeback extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_chargebacks';
    protected $fillable =['venta_id','fecha','valor','id_tipo_chargeback','porcentaje'];
    private static $whiteListFilter = [
        '*',
    ];
    public function venta(){
        return $this->hasOne(Ventas::class,'id','venta_id');
    }
    public function tipo_chargeback(){
        return $this->hasOne(TipoChargeback::class,'id','id_tipo_chargeback');
    }

}
