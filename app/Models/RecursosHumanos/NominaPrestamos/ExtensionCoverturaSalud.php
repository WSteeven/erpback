<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud
 *
 * @property int $id
 * @property string $mes
 * @property int $empleado_id
 * @property int $dependiente
 * @property string $origen
 * @property string $materia_grabada
 * @property string $aporte
 * @property string $aporte_porcentaje
 * @property bool $aprobado
 * @property string|null $observacion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Familiares|null $dependiente_info
 * @property-read Empleado|null $empleado_info
 * @method static Builder|ExtensionCoverturaSalud acceptRequest(?array $request = null)
 * @method static Builder|ExtensionCoverturaSalud filter(?array $request = null)
 * @method static Builder|ExtensionCoverturaSalud ignoreRequest(?array $request = null)
 * @method static Builder|ExtensionCoverturaSalud newModelQuery()
 * @method static Builder|ExtensionCoverturaSalud newQuery()
 * @method static Builder|ExtensionCoverturaSalud query()
 * @method static Builder|ExtensionCoverturaSalud setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ExtensionCoverturaSalud setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ExtensionCoverturaSalud setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ExtensionCoverturaSalud whereAporte($value)
 * @method static Builder|ExtensionCoverturaSalud whereAportePorcentaje($value)
 * @method static Builder|ExtensionCoverturaSalud whereAprobado($value)
 * @method static Builder|ExtensionCoverturaSalud whereCreatedAt($value)
 * @method static Builder|ExtensionCoverturaSalud whereDependiente($value)
 * @method static Builder|ExtensionCoverturaSalud whereEmpleadoId($value)
 * @method static Builder|ExtensionCoverturaSalud whereId($value)
 * @method static Builder|ExtensionCoverturaSalud whereMateriaGrabada($value)
 * @method static Builder|ExtensionCoverturaSalud whereMes($value)
 * @method static Builder|ExtensionCoverturaSalud whereObservacion($value)
 * @method static Builder|ExtensionCoverturaSalud whereOrigen($value)
 * @method static Builder|ExtensionCoverturaSalud whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ExtensionCoverturaSalud extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'extension_cobertura_salud';
    protected $fillable = [
        'mes','empleado_id','dependiente','origen','materia_grabada','aporte','aporte_porcentaje','aprobado','observacion'
    ];
    protected $casts = [
        'aporte' => 'decimal:2',
        'aprobado' =>'boolean',
    ];
    private static array $whiteListFilter = [
        'id',
        'empleado',
        'mes',
        'dependiente',
        'origen',
        'materia_grabada',
        'aporte',
        'aporte_porcentaje',
        'aprobado',
        'observacion'
    ];

    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }
    public function dependiente_info()
    {
        return $this->belongsTo(Familiares::class, 'dependiente', 'id');
    }
}
