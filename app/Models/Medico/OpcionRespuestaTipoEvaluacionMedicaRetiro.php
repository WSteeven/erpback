<?php

namespace App\Models\Medico;


use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\OpcionRespuestaTipoEvaluacionMedicaRetiro
 *
 * @property int $id
 * @property string $respuesta
 * @property int $tipo_evaluacion_medica_retiro_id
 * @property int $ficha_aptitud_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro query()
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro whereFichaAptitudId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro whereRespuesta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro whereTipoEvaluacionMedicaRetiroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpcionRespuestaTipoEvaluacionMedicaRetiro whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OpcionRespuestaTipoEvaluacionMedicaRetiro extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_opciones_respuestas_tipos_evaluaciones_medicas_retiros';
    protected $fillable = [
        'respuesta',
        'tipo_evaluacion_medica_retiro_id',
        'ficha_aptitud_id',
    ];

    private static $whiteListFilter = ['*'];
}
