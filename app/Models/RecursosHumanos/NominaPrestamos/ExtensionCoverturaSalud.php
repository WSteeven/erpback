<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\RecursosHumanos\NominaPrestamos\Familiares|null $dependiente_info
 * @property-read Empleado|null $empleado_info
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereAporte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereAportePorcentaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereAprobado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereDependiente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereMateriaGrabada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereOrigen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtensionCoverturaSalud whereUpdatedAt($value)
 * @mixin \Eloquent
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
    private static $whiteListFilter = [
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
