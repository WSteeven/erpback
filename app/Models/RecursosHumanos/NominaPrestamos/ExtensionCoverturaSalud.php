<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
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
    protected $table = 'extension_cobertura_salud';
    protected $fillable = [
        'mes','empleado_id','dependiente','origen','materia_grabada','aporte','aporte_porcentaje','aprobado','observacion'
    ];
    protected $casts = [
        'aporte' => 'decimal:2',
        'aprobado' =>'boolean',
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

    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }
    public function dependiente_info()
    {
        return $this->belongsTo(Familiares::class, 'dependiente', 'id');
    }
}
