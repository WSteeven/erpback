<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class PagoComision extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_pago_comision';
    protected $fillable =['mes','vendedor_id','chargeback_id','valor'];
    private static $whiteListFilter = [
        '*',
    ];
    public function vendedor(){
        return $this->hasOne(Vendedor::class,'id','vendedor_id')->with('empleado');
    }
    public function chargeback(){
        return $this->hasOne(Chargebacks::class,'id','ventas_chargebacks')->with('tipo_chargeback');
    }
}
