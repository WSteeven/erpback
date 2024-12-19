<?php

namespace App\Models;

use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Src\Config\EstadosTransacciones;

/**
 * App\Models\DetallePedidoProducto
 *
 * @property int $id
 * @property int $detalle_id
 * @property int $pedido_id
 * @property int|null $solicitante_id
 * @property int $cantidad
 * @property int $despachado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read DetalleProducto|null $detalleProducto
 * @property-read Empleado|null $solicitante
 * @method static Builder|DetallePedidoProducto acceptRequest(?array $request = null)
 * @method static Builder|DetallePedidoProducto filter(?array $request = null)
 * @method static Builder|DetallePedidoProducto ignoreRequest(?array $request = null)
 * @method static Builder|DetallePedidoProducto newModelQuery()
 * @method static Builder|DetallePedidoProducto newQuery()
 * @method static Builder|DetallePedidoProducto query()
 * @method static Builder|DetallePedidoProducto setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|DetallePedidoProducto setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|DetallePedidoProducto setLoadInjectedDetection($load_default_detection)
 * @method static Builder|DetallePedidoProducto whereCantidad($value)
 * @method static Builder|DetallePedidoProducto whereCreatedAt($value)
 * @method static Builder|DetallePedidoProducto whereDespachado($value)
 * @method static Builder|DetallePedidoProducto whereDetalleId($value)
 * @method static Builder|DetallePedidoProducto whereId($value)
 * @method static Builder|DetallePedidoProducto where($key, $value)
 * @method static Builder|DetallePedidoProducto wherePedidoId($value)
 * @method static Builder|DetallePedidoProducto whereSolicitanteId($value)
 * @method static Builder|DetallePedidoProducto whereUpdatedAt($value)
 * @mixin Eloquent
 */
class DetallePedidoProducto extends Pivot implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;

    public $incrementing = true;

    protected $table = 'detalle_pedido_producto';
    protected $fillable = [
        'detalle_id',
        'pedido_id',
        'cantidad',
        'despachado',
        'solicitante_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = ['*'];

    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /************************************************************************************************
     * FUNCIONES
     ************************************************************************************************/
    /**
     * La función "verificarDespachoItems" comprueba si todos los artículos de un pedido determinado
     * han sido despachados y actualiza el estado del pedido en consecuencia.
     *
     * @param DetallePedidoProducto $detallePedidoProducto El parámetro `` es un objeto que representa
     * un detalle específico de un producto en un pedido. Es probable que contenga información como
     * pedido_id, detalle_id, cantidad (cantidad solicitada) y despachado (cantidad despachada) del producto.
     */
    public static function verificarDespachoItems(DetallePedidoProducto $detallePedidoProducto)
    {
        // $estadoCompleta = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        // $estadoParcial = EstadoTransaccion::where('nombre', EstadoTransaccion::PARCIAL)->first();

        $resultados = DB::select('select count(*) as cantidad from detalle_pedido_producto dpp where dpp.pedido_id=' . $detallePedidoProducto->pedido_id . ' and dpp.cantidad!=dpp.despachado');
        $pedido = Pedido::find($detallePedidoProducto->pedido_id);
        $detallesSinDespachar = $pedido->detalles()->where('despachado', 0)->count();

        if ($detallesSinDespachar === $pedido->detalles()->count()) $pedido->update(['estado_id' => EstadosTransacciones::PENDIENTE]);
        else {
            if ($resultados[0]->cantidad > 0) $pedido->update(['estado_id' => EstadosTransacciones::PARCIAL]);
            else $pedido->update(['estado_id' => EstadosTransacciones::COMPLETA]);
        }
    }
}
