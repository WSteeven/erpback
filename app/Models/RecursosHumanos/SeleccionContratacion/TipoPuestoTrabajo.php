<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TipoPuestoTrabajo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_tipos_puestos_trabajos';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = [
        'nombre'
    ];
}
