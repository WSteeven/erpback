<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\FichaReintegro
 *
 * @property int $id
 * @property string $fecha_ultimo_dia_laboral
 * @property string $fecha_reingreso
 * @property string $causa_salida
 * @property string $motivo_consulta
 * @property string|null $enfermedad_actual
 * @property string|null $observacion_examen_fisico_regional
 * @property int|null $cargo_id
 * @property int $profesional_salud_id
 * @property int $registro_empleado_examen_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Medico\AptitudMedica|null $aptitudesMedicas
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\ConstanteVital|null $constanteVital
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\ExamenFisicoRegional> $examenesFisicosRegionales
 * @property-read int|null $examenes_fisicos_regionales_count
 * @property-read \App\Models\Medico\RegistroEmpleadoExamen|null $registroEmpleadoExamen
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro query()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereCausaSalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereEnfermedadActual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereFechaReingreso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereFechaUltimoDiaLaboral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereMotivoConsulta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereObservacionExamenFisicoRegional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereProfesionalSaludId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereRegistroEmpleadoExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaReintegro whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FichaReintegro extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_fichas_reintegros';
    protected $fillable = [
        'fecha_ultimo_dia_laboral',
        'fecha_reingreso',
        'causa_salida',
        //
        'motivo_consulta',
        'enfermedad_actual',
        'observacion_examen_fisico_regional',
        'cargo_id',
        'profesional_salud_id',
        'registro_empleado_examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }

    public function constanteVital()
    {
        return $this->morphOne(ConstanteVital::class, 'constanteVitalable', 'constante_vitalable_type', 'constante_vitalable_id');
    }

    public function examenesFisicosRegionales()
    {
        return $this->morphMany(ExamenFisicoRegional::class, 'examenFisicoRegionalable', 'examen_fisico_regionalable_type', 'examen_fisico_regionalable_id');
    }

    public function aptitudesMedicas()
    {
        return $this->morphOne(AptitudMedica::class, 'aptitudable');
    }
}
