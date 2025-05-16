<?php

namespace App\Models\Medico;

use App\ModelFilters\Medico\FichaAptitudFilter;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\FichaAptitud
 *
 * @property int $id
 * @property string|null $recomendaciones
 * @property string|null $observaciones_aptitud_medica
 * @property int $firmado_profesional_salud
 * @property int $firmado_paciente
 * @property int $registro_empleado_examen_id
 * @property int $tipo_aptitud_medica_laboral_id
 * @property int $profesional_salud_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\OpcionRespuestaTipoEvaluacionMedicaRetiro> $opcionesRespuestasTipoEvaluacionMedicaRetiro
 * @property-read int|null $opciones_respuestas_tipo_evaluacion_medica_retiro_count
 * @property-read \App\Models\Medico\ProfesionalSalud|null $profesionalSalud
 * @property-read \App\Models\Medico\RegistroEmpleadoExamen|null $registroEmpleadoExamen
 * @property-read \App\Models\Medico\TipoAptitudMedicaLaboral|null $tipoAptitudMedicaLaboral
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud query()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereFirmadoPaciente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereFirmadoProfesionalSalud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereObservacionesAptitudMedica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereProfesionalSaludId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereRecomendaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereRegistroEmpleadoExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereTipoAptitudMedicaLaboralId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaAptitud whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FichaAptitud extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, FichaAptitudFilter;

    protected $table = 'med_fichas_aptitudes';
    protected $fillable = [
        'recomendaciones',
        'observaciones_aptitud_medica',
        'firmado_profesional_salud',
        'firmado_paciente',
        'registro_empleado_examen_id',
        'tipo_aptitud_medica_laboral_id',
        'profesional_salud_id',
    ];

    private static $whiteListFilter = ['*'];

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }

    public function tipoAptitudMedicaLaboral()
    {
        return $this->belongsTo(TipoAptitudMedicaLaboral::class, 'tipo_aptitud_medica_laboral_id');
    }

    public function profesionalSalud()
    {
        return $this->belongsTo(ProfesionalSalud::class, 'id', '');//, 'id', 'ficha_aptitud_id');
        // return $this->hasOne(ProfesionalSalud::class, 'id', 'ficha_aptitud_id');
    }

    public function opcionesRespuestasTipoEvaluacionMedicaRetiro()
    {
        return $this->hasMany(OpcionRespuestaTipoEvaluacionMedicaRetiro::class);
    }
}
