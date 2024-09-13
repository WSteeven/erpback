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
/**
 * App\Models\Medico\EstadoSolicitudExamen
 *
 * @property int $id
 * @property string $fecha_hora_asistencia
 * @property int $examen_id
 * @property int $laboratorio_clinico_id
 * @property int $solicitud_examen_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\DetalleResultadoExamen|null $detalleResultadoExamen
 * @property-read \App\Models\Medico\Examen|null $examen
 * @property-read \App\Models\Medico\LaboratorioClinico|null $laboratorioClinico
 * @property-read \App\Models\Medico\SolicitudExamen|null $solicitudExamen
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen whereExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen whereFechaHoraAsistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen whereLaboratorioClinicoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen whereSolicitudExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitudExamen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EstadoSolicitudExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, EstadoSolicitudExamenFilter;

    protected $table = 'med_examenes_solicitados';
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
