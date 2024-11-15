<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\Vacacion
 *
 * @property int $id
 * @property int $empleado_id
 * @property int $periodo_id
 * @property int $dias
 * @property int $opto_pago
 * @property boolean $completadas
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado $empleado
 * @property-read Periodo $periodo
 * @method static Builder|Vacacion acceptRequest(?array $request = null)
 * @method static Builder|Vacacion filter(?array $request = null)
 * @method static Builder|Vacacion ignoreRequest(?array $request = null)
 * @method static Builder|Vacacion newModelQuery()
 * @method static Builder|Vacacion newQuery()
 * @method static Builder|Vacacion query()
 * @method static Builder|Vacacion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Vacacion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Vacacion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Vacacion whereCompletadas($value)
 * @method static Builder|Vacacion whereCreatedAt($value)
 * @method static Builder|Vacacion whereDias($value)
 * @method static Builder|Vacacion whereEmpleadoId($value)
 * @method static Builder|Vacacion whereId($value)
 * @method static Builder|Vacacion whereOptoPago($value)
 * @method static Builder|Vacacion wherePeriodoId($value)
 * @method static Builder|Vacacion whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Vacacion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;
    protected $table = 'rrhh_nomina_vacaciones';
    protected $fillable = [
    'empleado_id',
    'periodo_id',
    'dias',
    'opto_pago',
    'completadas',
    'observacion',
    'mes_pago',
    ];

    protected $casts = [
        'opto_pago'=> 'boolean',
        'completadas'=> 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }

    public function periodo(){
        return $this->belongsTo(Periodo::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVacacion::class);
    }

    public function valoresRolMensualEmpleado()
    {
        return $this->morphMany(ValorEmpleadoRolMensual::class, 'valorable','model_type','model_id');
    }
}
