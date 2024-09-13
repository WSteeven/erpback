<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * App\Models\Vehiculos\PlanMantenimiento
 *
 * @method static where(string $string, $vehiculo_id)
 * @property int $id
 * @property int $vehiculo_id
 * @property int $servicio_id
 * @property int $aplicar_desde
 * @property int $aplicar_cada
 * @property int|null $notificar_antes
 * @property string|null $datos_adicionales
 * @property int $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereAplicarCada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereAplicarDesde($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereDatosAdicionales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereNotificarAntes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereServicioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanMantenimiento whereVehiculoId($value)
 * @mixin \Eloquent
 */
class PlanMantenimiento extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'veh_planes_mantenimientos';
    protected $fillable = [
        'vehiculo_id',
        'servicio_id',
        'aplicar_desde',
        'aplicar_cada',
        'notificar_antes',
        'datos_adicionales',
        'activo',
    ];

    protected array $auditInclude = ['*'];

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    public static function eliminarObsoletos($vehiculo_id, $ids_servicios)
    {
        $itemsNoEncontrados = PlanMantenimiento::where('vehiculo_id', $vehiculo_id)
            ->whereNotIn('servicio_id', $ids_servicios)->delete();
        Log::channel('testing')->info('Log', ['items para eliminar', $itemsNoEncontrados]);
    }
}
