<?php

namespace App\Models\Medico;

use App\ModelFilters\EstadoSolicitudExamenFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

// Examen solicitado
class EstadoSolicitudExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, EstadoSolicitudExamenFilter;

    protected $table = 'med_estados_solicitudes_examenes';
    protected $fillable = [
        'examen_id',
        'laboratorio_clinico_id',
        'fecha_hora_asistencia',
        'solicitud_examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function examen()
    {
        return $this->hasOne(Examen::class, 'id', 'examen_id');
    }

    public function laboratorioClinico()
    {
        return $this->belongsTo(LaboratorioClinico::class);
    }

    public function detalleResultadoExamen()
    {
        return $this->hasOne(DetalleResultadoExamen::class);
    }

    public function solicitudExamen()
    {
        return $this->belongsTo(SolicitudExamen::class);
    }
}
