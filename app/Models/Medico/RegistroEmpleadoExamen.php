<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\RegistroEmpleadoExamen
 *
 * @property int $id
 * @property string $observacion
 * @property string $tipo_proceso_examen
 * @property int $numero_registro
 * @property int $empleado_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\EstadoSolicitudExamen> $estadosSolicitudesExamenes
 * @property-read int|null $estados_solicitudes_examenes_count
 * @property-read \App\Models\Medico\FichaAptitud|null $fichaAptitud
 * @property-read \App\Models\Medico\FichaPeriodica|null $fichaPeriodica
 * @property-read \App\Models\Medico\FichaPreocupacional|null $fichaPreocupacional
 * @property-read \App\Models\Medico\FichaReintegro|null $fichaReintegro
 * @property-read \App\Models\Medico\FichaRetiro|null $fichaRetiro
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen whereNumeroRegistro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen whereTipoProcesoExamen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroEmpleadoExamen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RegistroEmpleadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    // Contantes
    const INGRESO = 'INGRESO';
    const PERIODICO = 'PERIODICO';
    const REINTEGRO = 'REINTEGRO';
    const RETIRO = 'RETIRO';

    protected $table = 'med_registros_empleados_examenes';
    protected $fillable = [
        'numero_registro',
        'observacion',
        'tipo_proceso_examen',
        'empleado_id',
    ];

    private static $whiteListFilter = ['*'];

    // Relaciones
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function estadosSolicitudesExamenes()
    {
        return $this->hasMany(EstadoSolicitudExamen::class, 'id', 'registro_empleado_examen_id')->with('examen', 'estadoExamen');
    }

    public function fichaAptitud()
    {
        return $this->hasOne(FichaAptitud::class);
    }

    public function fichaPreocupacional()
    {
        return $this->hasOne(FichaPreocupacional::class);
    }

    public function fichaPeriodica()
    {
        return $this->hasOne(FichaPeriodica::class);
    }

    public function fichaReintegro()
    {
        return $this->hasOne(FichaReintegro::class);
    }

    public function fichaRetiro()
    {
        return $this->hasOne(FichaRetiro::class);
    }
}
