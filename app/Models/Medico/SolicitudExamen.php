<?php

namespace App\Models\Medico;

use App\Models\Canton;
use App\Models\Empleado;
use App\Models\Notificacion;
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
        'observacion_autorizador',
        'registro_empleado_examen_id',
        'estado_solicitud_examen',
        'canton_id',
        'solicitante_id',
        'autorizador_id',
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

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }

    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
}
