<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
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
 * App\Models\ActivoFijo
 *
 * @property int $id
 * @property int $cantidad
 * @property string $fecha_desde
 * @property string|null $fecha_hasta
 * @property string $accion
 * @property string|null $observacion
 * @property int $detalle_id
 * @property int $empleado_id
 * @property int $sucursal_id
 * @property int $condicion_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Condicion $condicion
 * @property-read DetalleProducto $detalle
 * @property-read Empleado $empleado
 * @property-read Sucursal $sucursal
 * @method static Builder|ActivoFijo acceptRequest(?array $request = null)
 * @method static Builder|ActivoFijo filter(?array $request = null)
 * @method static Builder|ActivoFijo ignoreRequest(?array $request = null)
 * @method static Builder|ActivoFijo newModelQuery()
 * @method static Builder|ActivoFijo newQuery()
 * @method static Builder|ActivoFijo query()
 * @method static Builder|ActivoFijo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ActivoFijo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ActivoFijo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ActivoFijo whereAccion($value)
 * @method static Builder|ActivoFijo whereCantidad($value)
 * @method static Builder|ActivoFijo whereCondicionId($value)
 * @method static Builder|ActivoFijo whereCreatedAt($value)
 * @method static Builder|ActivoFijo whereDetalleId($value)
 * @method static Builder|ActivoFijo whereEmpleadoId($value)
 * @method static Builder|ActivoFijo whereFechaDesde($value)
 * @method static Builder|ActivoFijo whereFechaHasta($value)
 * @method static Builder|ActivoFijo whereId($value)
 * @method static Builder|ActivoFijo whereObservacion($value)
 * @method static Builder|ActivoFijo whereSucursalId($value)
 * @method static Builder|ActivoFijo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ActivoFijo extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use AuditableModel;
    use UppercaseValuesTrait;
    protected $table = 'activos_fijos';
    protected $fillable = [
        'cantidad',
        'fecha_desde',
        'fecha_hasta',
        'accion',
        'observacion',
        // 'lugar',
        'detalle_id',
        'empleado_id',
        'sucursal_id',
        'condicion_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    const ASIGNACION = 'ASIGNACION'; //cuando se entrega el activo al empleado
    const DEVOLUCION = 'DEVOLUCION'; //cuando devuelve el activo a bodega

    /**
     * Relación uno a muchos(inversa).
     * Uno o muchos activos fijos estan asignados a un empleado.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
    /**
     * Relación uno a uno.
     * Un activos es un detalle.
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    /**
     * Relación uno a muchos (inversa).
     * Un activo fijo tiene una condicion.
     */
    public function condicion()
    {
        return $this->belongsTo(Condicion::class);
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o muchos activos fijos estan en una sucursal.
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
