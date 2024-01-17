<?php

namespace App\Models\Ventas;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Vendedor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_vendedores';
    protected $fillable =['empleado_id','modalidad_id','tipo_vendedor','jefe_inmediato','jefe_inmediato_id'];
    private static $whiteListFilter = [
        '*',
    ];
    public function empleado(){
        return $this->hasOne(Empleado::class,'id','empleado_id')->with('canton');
    }
    public function modalidad(){
        return $this->hasOne(Modalidad::class,'id','modalidad_id');
    }
    public function jefe_inmediato(){
        return $this->hasOne(Empleado::class,'id','jefe_inmediato_id')->with('canton');
    }
}
