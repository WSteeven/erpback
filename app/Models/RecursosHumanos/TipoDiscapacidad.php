<?php

namespace App\Models\RecursosHumanos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TipoDiscapacidad extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'rrhh_tipos_discapacidades';
    protected $fillable = ['nombre'];

    private static $whiteListFilter = [" *"];

    public function empleados(){
        return $this->belongsToMany(Empleado::class,'rrhh_empleado_tipo_discapacidad_porcentaje');
    }
}
