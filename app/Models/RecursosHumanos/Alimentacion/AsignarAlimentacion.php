<?php

namespace App\Models\RecursosHumanos\Alimentacion;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class AsignarAlimentacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_asignar_alimentacion';
    protected $fillable = [
        'empleado_id',
        'valor_minimo'
    ];

    private static $whiteListFilter = [
        'empleado_id',
        'empleado',
        'valor_minimo'
    ];
    public function empleado(){
        return $this->hasOne(Empleado::class,'id','empleado_id');
    }

}
