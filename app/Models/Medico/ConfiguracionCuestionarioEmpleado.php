<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\Medico\ConfiguracionCuestionarioEmpleado
 *
 * @property int $id
 * @property string $fecha_hora_inicio
 * @property string $fecha_hora_fin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado whereFechaHoraFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado whereFechaHoraInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionCuestionarioEmpleado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConfiguracionCuestionarioEmpleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_configuraciones_cuestionarios_empleados';
    protected $fillable = [
        'fecha_hora_inicio',
        'fecha_hora_fin'
    ];
    private static $whiteListFilter = ['*'];


}
