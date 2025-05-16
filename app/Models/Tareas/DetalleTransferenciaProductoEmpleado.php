<?php

namespace App\Models\Tareas;

use App\Models\DetalleProducto;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Tareas\DetalleTransferenciaProductoEmpleado
 *
 * @property int $id
 * @property int $detalle_producto_id
 * @property int $transf_produc_emplea_id
 * @property int $cantidad
 * @property int $cliente_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read DetalleProducto|null $detalleProducto
 * @property-read \App\Models\Tareas\TransferenciaProductoEmpleado|null $transferencia
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado whereDetalleProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado whereTransfProducEmpleaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTransferenciaProductoEmpleado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetalleTransferenciaProductoEmpleado extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'tar_det_tran_prod_emp';

    protected $fillable = [
        'detalle_producto_id',
        'transf_produc_emplea_id',
        'cantidad',
        'cliente_id',
    ];

    private static $whiteListFilter = ['*'];

    /**************
     * Relaciones
     **************/
    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class);
    }
    public function transferencia(){
        return $this->belongsTo(TransferenciaProductoEmpleado::class, 'transf_produc_emplea_id');
    }
}
