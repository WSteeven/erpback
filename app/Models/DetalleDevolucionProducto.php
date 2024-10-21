<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * App\Models\DetalleDevolucionProducto
 *
 * @property int $id
 * @property int $detalle_id
 * @property int $devolucion_id
 * @property int $cantidad
 * @property int $devuelto
 * @property int|null $condicion_id
 * @property string|null $observacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Condicion|null $condicion
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto whereCondicionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto whereDetalleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto whereDevolucionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto whereDevuelto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDevolucionProducto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetalleDevolucionProducto extends Pivot implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;
    protected $table ='detalle_devolucion_producto';
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
        'detalle_id',
        'devolucion_id',
        'observacion',
        'condicion_id',
        'cantidad',
        'devuelto',
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

     public function condicion(){
        return $this->belongsTo(Condicion::class);
     }


}
