<?php

namespace App\Models\RecursosHumanos\Alimentacion;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DetalleAlimentacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_detalle_alimentaciones';
    protected $fillable = [
        'empleado_id',
        'valor_asignado',
        'fecha_corte',
        'alimentacion_id'
    ];

    private static $whiteListFilter = [
        'empleado_id',
        'empleado',
        'valor_asignado',
        'fecha_corte',
        'alimentacion',
        'alimentacion_id'
    ];
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id');
    }
    public function alimentacion(){
        return $this->hasOne(Alimentacion::class, 'id', 'alimentacion_id');
    }
}
