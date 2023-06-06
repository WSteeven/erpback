<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ExtensionCoverturaSalud extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'prestamo_hipotecario';
    protected $fillable = [
        'mes','empleado_id','dependiente','origen','materia_grabada','aporte','aporte_porcentaje','aprobado','observacion'
    ];
    protected $casts = [
        'aporte' => 'decimal:2'
    ];
    private static $whiteListFilter = [
        'id',
        'empleado',
        'mes',
        'dependiente',
        'origen',
        'materia_grabada',
        'aporte',
        'aporte_porcentaje',
        'aprobado',
        'observacion'
    ];
}
