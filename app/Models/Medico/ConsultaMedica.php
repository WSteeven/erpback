<?php

namespace App\Models\Medico;

use App\ModelFilters\Medico\ConsultaMedicaFilter;
use App\Models\Archivo;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\ConsultaMedica
 *
 * @property int $id
 * @property string|null $evolucion
 * @property int $dado_alta
 * @property int $dias_descanso
 * @property int|null $cita_medica_id
 * @property int|null $registro_empleado_examen_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $examen_fisico
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read CitaMedica|null $citaMedica
 * @property-read ConstanteVital|null $constanteVital
 * @property-read Collection<int, DiagnosticoCitaMedica> $diagnosticosCitaMedica
 * @property-read int|null $diagnosticos_cita_medica_count
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Receta|null $receta
 * @property-read RegistroEmpleadoExamen|null $registroEmpleadoExamen
 * @method static Builder|ConsultaMedica acceptRequest(?array $request = null)
 * @method static Builder|ConsultaMedica filter(?array $request = null)
 * @method static Builder|ConsultaMedica ignoreRequest(?array $request = null)
 * @method static Builder|ConsultaMedica newModelQuery()
 * @method static Builder|ConsultaMedica newQuery()
 * @method static Builder|ConsultaMedica query()
 * @method static Builder|ConsultaMedica setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ConsultaMedica setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ConsultaMedica setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ConsultaMedica whereCitaMedicaId($value)
 * @method static Builder|ConsultaMedica whereCreatedAt($value)
 * @method static Builder|ConsultaMedica whereDadoAlta($value)
 * @method static Builder|ConsultaMedica whereDiasDescanso($value)
 * @method static Builder|ConsultaMedica whereEvolucion($value)
 * @method static Builder|ConsultaMedica whereExamenFisico($value)
 * @method static Builder|ConsultaMedica whereId($value)
 * @method static Builder|ConsultaMedica whereRegistroEmpleadoExamenId($value)
 * @method static Builder|ConsultaMedica whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ConsultaMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, ConsultaMedicaFilter;

    protected $table = 'med_consultas_medicas';

    protected $fillable = [
        'evolucion',
        'examen_fisico',
        'dado_alta',
        'dias_descanso',
        'cita_medica_id',
        'registro_empleado_examen_id',
        'restricciones_alta',
        'observaciones_alta',
    ];

    private static array $whiteListFilter = ['*'];

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }

    public function citaMedica()
    {
        return $this->belongsTo(CitaMedica::class);
    }

    public function receta()
    {
        return $this->hasOne(Receta::class);
    }

    public function diagnosticosCitaMedica()
    {
        return $this->hasMany(DiagnosticoCitaMedica::class);
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function constanteVital()
    {
        return $this->morphOne(ConstanteVital::class, 'constanteVitalable', 'constante_vitalable_type', 'constante_vitalable_id');
    }
}
