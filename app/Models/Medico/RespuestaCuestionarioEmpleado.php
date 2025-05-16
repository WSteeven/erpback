<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Src\App\Medico\CuestionarioPisicosocialService;

/**
 * App\Models\Medico\RespuestaCuestionarioEmpleado
 *
 * @property int $id
 * @property int $cuestionario_id
 * @property string|null $respuesta_texto
 * @property int $empleado_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\Cuestionario|null $cuestionario
 * @property-read Empleado|null $empleado
 * @method static Builder|RespuestaCuestionarioEmpleado acceptRequest(?array $request = null)
 * @method static Builder|RespuestaCuestionarioEmpleado filter(?array $request = null)
 * @method static Builder|RespuestaCuestionarioEmpleado ignoreRequest(?array $request = null)
 * @method static Builder|RespuestaCuestionarioEmpleado newModelQuery()
 * @method static Builder|RespuestaCuestionarioEmpleado newQuery()
 * @method static Builder|RespuestaCuestionarioEmpleado query()
 * @method static Builder|RespuestaCuestionarioEmpleado setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|RespuestaCuestionarioEmpleado setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|RespuestaCuestionarioEmpleado setLoadInjectedDetection($load_default_detection)
 * @method static Builder|RespuestaCuestionarioEmpleado whereCreatedAt($value)
 * @method static Builder|RespuestaCuestionarioEmpleado whereCuestionarioId($value)
 * @method static Builder|RespuestaCuestionarioEmpleado whereEmpleadoId($value)
 * @method static Builder|RespuestaCuestionarioEmpleado whereId($value)
 * @method static Builder|RespuestaCuestionarioEmpleado whereRespuestaTexto($value)
 * @method static Builder|RespuestaCuestionarioEmpleado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RespuestaCuestionarioEmpleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_respuestas_cuestionarios_empleados';
    protected $fillable = [
        'cuestionario_id',
        'respuesta',
        'empleado_id',
        'respuesta_texto',
    ];
    private static $whiteListFilter = ['*'];

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'cuestionario_id')->with('pregunta');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
