<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class BancoHorasMovimiento extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_nomina_banco_horas_movimientos';
    protected $fillable = [
        'empleado_id',
        'tipo',
        'horas',
        'fecha_movimiento',
        'descontado',
        'model_id',
        'model_type',
        'detalle'
    ];

    const TIPO_PERMISO_NO_RECUPERADO = 'PERMISO NO RECUPERADO';

    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }


    public function modelable(){
        return $this->morphTo();
    }

}
