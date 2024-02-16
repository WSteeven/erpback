<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class BonoMensualCumplimiento extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_bonos_mensuales_cumplimientos';
    protected $fillable = [
        'vendedor_id',
        'cant_ventas',
        'mes',
        'bono_id',
        'valor',
    ];
    private static $whiteListFilter = [
        '*',
    ];
    public function vendedor(){
        return $this->hasOne(Vendedor::class,'empleado_id','vendedor_id')->with('empleado');
    }
    public function bono(){
        return $this->hasOne(Bono::class,'id','bono_id');
    }
}
