<?php

namespace App\Models\Medico;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;

class SolicitudExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_solicitudes_examenes';
    protected $fillable = [
        'observacion',
        'registro_empleado_examen_id',
        'estado_solicitud_examen',
    ];

    // Estados solicitudes examenes
    const PENDIENTE = 'PENDIENTE';
    const SOLICITADO = 'SOLICITADO';
    const APROBADO_POR_COMPRAS = 'APROBADO_POR_COMPRAS';
    const RESULTADOS = 'RESULTADOS';
    const DIAGNOSTICO_REALIZADO = 'DIAGNOSTICO_REALIZADO';

    private static $whiteListFilter = ['*'];

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }

    public function examenesSolicitados()
    {
        return $this->hasMany(EstadoSolicitudExamen::class);
    }
}
