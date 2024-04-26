<?php

namespace App\Models\Medico;

use App\Models\Archivo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class DetalleResultadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;
    protected $table = 'med_detalles_resultados_examenes';
    protected $fillable = [
        'estado_solicitud_examen_id',
    ];
    private static $whiteListFilter = ['*'];

    public function estadoSolicitudExamen()
    {
        return $this->belongsTo(EstadoSolicitudExamen::class);
    }

    public function resultadosExamenes()
    {
        return $this->hasMany(ResultadoExamen::class);
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
