<?php

namespace App\Models\Medico;

use App\ModelFilters\EstadoSolicitudExamenFilter;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

// Examen solicitado
/**
 * App\Models\Medico\EstadoSolicitudExamen
 *
 * @property int $id
 * @property string $fecha_hora_asistencia
 * @property int $examen_id
 * @property int $laboratorio_clinico_id
 * @property int $solicitud_examen_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read DetalleResultadoExamen|null $detalleResultadoExamen
 * @property-read Examen|null $examen
 * @property-read LaboratorioClinico|null $laboratorioClinico
 * @property-read SolicitudExamen|null $solicitudExamen
 * @method static Builder|EstadoSolicitudExamen acceptRequest(?array $request = null)
 * @method static Builder|EstadoSolicitudExamen filter(?array $request = null)
 * @method static Builder|EstadoSolicitudExamen ignoreRequest(?array $request = null)
 * @method static Builder|EstadoSolicitudExamen newModelQuery()
 * @method static Builder|EstadoSolicitudExamen newQuery()
 * @method static Builder|EstadoSolicitudExamen query()
 * @method static Builder|EstadoSolicitudExamen setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|EstadoSolicitudExamen setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|EstadoSolicitudExamen setLoadInjectedDetection($load_default_detection)
 * @method static Builder|EstadoSolicitudExamen whereCreatedAt($value)
 * @method static Builder|EstadoSolicitudExamen whereExamenId($value)
 * @method static Builder|EstadoSolicitudExamen whereFechaHoraAsistencia($value)
 * @method static Builder|EstadoSolicitudExamen whereId($value)
 * @method static Builder|EstadoSolicitudExamen whereLaboratorioClinicoId($value)
 * @method static Builder|EstadoSolicitudExamen whereSolicitudExamenId($value)
 * @method static Builder|EstadoSolicitudExamen whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = ['*'];

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
