<?php

namespace App\Models\Medico;

use App\Models\Empleado;
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
 * App\Models\Medico\RegistroEmpleadoExamen
 *
 * @property int $id
 * @property string $observacion
 * @property string $tipo_proceso_examen
 * @property int $numero_registro
 * @property int $empleado_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @property-read Collection<int, EstadoSolicitudExamen> $estadosSolicitudesExamenes
 * @property-read int|null $estados_solicitudes_examenes_count
 * @property-read FichaAptitud|null $fichaAptitud
 * @property-read FichaPeriodica|null $fichaPeriodica
 * @property-read FichaPreocupacional|null $fichaPreocupacional
 * @property-read FichaReintegro|null $fichaReintegro
 * @property-read FichaRetiro|null $fichaRetiro
 * @method static Builder|RegistroEmpleadoExamen acceptRequest(?array $request = null)
 * @method static Builder|RegistroEmpleadoExamen filter(?array $request = null)
 * @method static Builder|RegistroEmpleadoExamen ignoreRequest(?array $request = null)
 * @method static Builder|RegistroEmpleadoExamen newModelQuery()
 * @method static Builder|RegistroEmpleadoExamen newQuery()
 * @method static Builder|RegistroEmpleadoExamen query()
 * @method static Builder|RegistroEmpleadoExamen setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|RegistroEmpleadoExamen setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|RegistroEmpleadoExamen setLoadInjectedDetection($load_default_detection)
 * @method static Builder|RegistroEmpleadoExamen whereCreatedAt($value)
 * @method static Builder|RegistroEmpleadoExamen whereEmpleadoId($value)
 * @method static Builder|RegistroEmpleadoExamen whereId($value)
 * @method static Builder|RegistroEmpleadoExamen whereNumeroRegistro($value)
 * @method static Builder|RegistroEmpleadoExamen whereObservacion($value)
 * @method static Builder|RegistroEmpleadoExamen whereTipoProcesoExamen($value)
 * @method static Builder|RegistroEmpleadoExamen whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = ['*'];

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
