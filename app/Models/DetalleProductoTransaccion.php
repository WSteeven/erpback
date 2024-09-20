<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevolucionTransaccion> $devoluciones
 * @property-read int|null $devoluciones_count
 * @property-read \App\Models\Inventario|null $inventario
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MovimientoProducto> $movimientos
 * @property-read int|null $movimientos_count
 * @property-read \App\Models\TransaccionBodega|null $transaccion
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion whereCantidadInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion whereInventarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion whereRecibido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion whereTransaccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion whereIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleProductoTransaccion whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = ['*'];

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
