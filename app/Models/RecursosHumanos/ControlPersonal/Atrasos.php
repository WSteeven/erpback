<?php

namespace App\Models\RecursosHumanos\ControlPersonal;

use App\Models\Empleado;
use App\Models\RecursosHumanos\ControlPersonal\Asistencia;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Atrasos extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_cp_atrasos';

    // Campos permitidos para inserción masiva
    protected $fillable = [
        'empleado_id',
        'asistencia_id',
        'fecha_atraso',
        'minutos_atraso',
        'segundos_atraso',
        'requiere_justificacion',
        'justificacion_atraso',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    // Relación con la tabla empleados
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación con la tabla asistencias
    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class);
    }

    // Casts para formatear automáticamente los valores
    protected $casts = [
        'fecha_atraso' => 'date:Y-m-d',
        'minutos_atraso' => 'integer',
        'segundos_atraso' => 'integer',
        'requiere_justificacion' => 'boolean',
    ];
}
