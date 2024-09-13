<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\DetalleInventarioTraspaso
 *
 * @property int $id
 * @property int $traspaso_id
 * @property int $inventario_id
 * @property int $cantidad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevolucionTraspaso> $devoluciones
 * @property-read int|null $devoluciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MovimientoProducto> $movimientos
 * @property-read int|null $movimientos_count
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso whereInventarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso whereTraspasoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTraspaso whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetalleInventarioTraspaso extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table='detalle_inventario_traspaso';
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
        'traspaso_id',
        'inventario_id',
        'cantidad',
        'devolucion',
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
     * Relacion uno a muchos polimorfica
     */
    public function movimientos(){
        return $this->morphMany('App\Models\MovimientoProducto', 'movimientable');
    }

    /**
     * Relación uno a muchos.
     * Un detalle de un traspaso tiene una o varias devoluciones
     */
    public function devoluciones(){
        return $this->hasMany(DevolucionTraspaso::class);
    }
}
