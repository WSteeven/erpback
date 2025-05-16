<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\ResultadoExamen
 *
 * @property int $id
 * @property string|null $resultado
 * @property int $configuracion_examen_campo_id
 * @property int $examen_solicitado_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $observaciones
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\ConfiguracionExamenCampo|null $configuracionExamenCampo
 * @property-read \App\Models\Medico\EstadoSolicitudExamen|null $estadoSolicitudExamen
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen whereConfiguracionExamenCampoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen whereExamenSolicitadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen whereResultado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ResultadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_resultados_examenes';
    protected $fillable = [
        'resultado',
        'observaciones',
        'configuracion_examen_campo_id',
        'examen_solicitado_id',
    ];
    private static $whiteListFilter = ['*'];

    public function configuracionExamenCampo()
    {
        return $this->belongsTo(ConfiguracionExamenCampo::class);
    }

    public function estadoSolicitudExamen()
    {
        return $this->hasOne(EstadoSolicitudExamen::class, 'examen_solicitado_id', 'id');
    }
}
