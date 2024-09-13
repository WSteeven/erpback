<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Condicion $condicion
 * @property-read \App\Models\DetalleProducto $detalle
 * @property-read \App\Models\Empleado $empleado
 * @property-read \App\Models\Sucursal $sucursal
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereAccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereCondicionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereDetalleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereFechaDesde($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereFechaHasta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereSucursalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivoFijo whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = [
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
