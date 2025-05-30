<?php

namespace App\Models;

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
 * App\Models\DetalleProductoTransaccion
 *
 * @method static whereIn(string $string, $ids_inventarios)
 * @method static where(string $string, $id)
 * @property int $id
 * @property int $inventario_id
 * @property int $transaccion_id
 * @property int $cantidad_inicial
 * @property int|null $recibido
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, DevolucionTransaccion> $devoluciones
 * @property-read int|null $devoluciones_count
 * @property-read Inventario|null $inventario
 * @property-read Collection<int, MovimientoProducto> $movimientos
 * @property-read int|null $movimientos_count
 * @property-read TransaccionBodega|null $transaccion
 * @method static Builder|DetalleProductoTransaccion acceptRequest(?array $request = null)
 * @method static Builder|DetalleProductoTransaccion filter(?array $request = null)
 * @method static Builder|DetalleProductoTransaccion ignoreRequest(?array $request = null)
 * @method static Builder|DetalleProductoTransaccion newModelQuery()
 * @method static Builder|DetalleProductoTransaccion newQuery()
 * @method static Builder|DetalleProductoTransaccion query()
 * @method static Builder|DetalleProductoTransaccion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|DetalleProductoTransaccion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|DetalleProductoTransaccion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|DetalleProductoTransaccion whereCantidadInicial($value)
 * @method static Builder|DetalleProductoTransaccion whereCreatedAt($value)
 * @method static Builder|DetalleProductoTransaccion whereId($value)
 * @method static Builder|DetalleProductoTransaccion whereInventarioId($value)
 * @method static Builder|DetalleProductoTransaccion whereRecibido($value)
 * @method static Builder|DetalleProductoTransaccion whereTransaccionId($value)
 * @method static Builder|DetalleProductoTransaccion whereUpdatedAt($value)
 * @method static Builder|DetalleProductoTransaccion where($value)
 * @mixin Eloquent
 */
class DetalleProductoTransaccion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'detalle_producto_transaccion';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'inventario_id',
        'transaccion_id',
        'cantidad_inicial',
        'recibido',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relacion uno a uno(inversa).
     * Un detalle_producto_transaccion pertenece a un detalle_producto.
     */
    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    /**
     * Relacion uno a uno(inversa).
     * Un detalle_producto_transaccion pertenece a una transaccion.
     */
    public function transaccion()
    {
        return $this->belongsTo(TransaccionBodega::class, 'transaccion_id', 'id');
    }

    /**
     * Relación uno a muchos.
     * Un detalle_producto_transaccion tiene varios movimientos
     */
    public function movimientos(){
        return $this->hasMany(MovimientoProducto::class);
    }

    /**
     * Relacion uno a muchos polimorfica
     */
    // public function movimientos(){
    //     // return $this->morphMany('App\Models\MovimientoProducto', 'movimientable');
    //     return $this->morphMany(MovimientoProducto::class, 'movimientable');
    // }
    /**
     * Relación uno a muchos.
     * Un detalle de una transaccion tiene una o varias devoluciones parciales
     */
    public function devoluciones(){
        return $this->hasMany(DevolucionTransaccion::class);
    }


}
