<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Src\Config\EstadosTransacciones;

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

    private static $whiteListFilter = ['*'];

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
     * @param detallePedidoProducto El parámetro `` es un objeto que representa
     * un detalle específico de un producto en un pedido. Es probable que contenga información como
     * pedido_id, detalle_id, cantidad (cantidad solicitada) y despachado (cantidad despachada) del producto.
     */
    public static function verificarDespachoItems($detallePedidoProducto)
    {
        // $estadoCompleta = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        // $estadoParcial = EstadoTransaccion::where('nombre', EstadoTransaccion::PARCIAL)->first();

        $resultados = DB::select('select count(*) as cantidad from detalle_pedido_producto dpp where dpp.pedido_id=' . $detallePedidoProducto->pedido_id . ' and dpp.cantidad!=dpp.despachado');
        $pedido = Pedido::find($detallePedidoProducto->pedido_id);
        $detallesSinDespachar = $pedido->detalles()->where('despachado', 0)->count();

        if ($resultados[0]->cantidad > 0) {
            $pedido->update(['estado_id' => EstadosTransacciones::PARCIAL]);
        } else {
            if ($detallesSinDespachar === $pedido->detalles()->count()) $pedido->update(['estado_id' => EstadosTransacciones::PENDIENTE]);
            else $pedido->update(['estado_id' => EstadosTransacciones::COMPLETA]);
        }
    }
}
