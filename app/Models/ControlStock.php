<?php

namespace App\Models;

use App\Models\ComprasProveedores\PreordenCompra;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class ControlStock extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'control_stocks';
    protected $fillable = [
        'detalle_id',
        'sucursal_id',
        'cliente_id',
        'minimo',
        'reorden',
        'estado',
    ];

    const SUFICIENTE = "STOCK SUFICIENTE";
    const REORDEN = "PROXIMO A AGOTARSE";
    const MINIMO = "DEBAJO DEL MINIMO";

    private static $whiteListFilter = ['*'];

    /**
     * Reglas de estados de control
     * cuando la sumatoria del stock de nuevos y usados en una misma bodega del inventario del producto con detalle_id #
     * Por ejemplo: detalle_id #1, hay 100 productos nuevos y 40 usados en la bodega de machala,
     * entonces se realiza un calculo en base a esa sumatoria y se comprueba si:
     * Si sumatoria es mayor al valor del punto de reorden , el estado debe ser @const SUFICIENTE
     * Si sumatoria es menor al punto de reorden , el estado debe ser @const REORDEN
     * Si sumatoria es menor al punto minimo, el estado debe ser @const MINIMO
     *
     * @return int $cantidad Cantidad de existencias
     */

    public static function controlExistencias($detalle_id, $sucursal_id, $cliente_id)
    {
        $cantidad = 0;
        $elementos = Inventario::where('detalle_id', $detalle_id)
            ->where('sucursal_id', $sucursal_id)
            ->where('cliente_id', $cliente_id)->get();

        // Log::channel('testing')->info('Log', ['listado de elemento', $elementos->isEmpty()]);
        if ($elementos->isEmpty()) return -1;
        foreach ($elementos as $elemento) {
            $cantidad += $elemento->cantidad;
        }
        return $cantidad;
    }

    /**
     * Método que calcula el estado para asignar
     */
    public static function calcularEstado(ControlStock $entidad, $cantidad, $minimo, $reorden){
        if ($cantidad <= $minimo) {
            //lanzar preorden de compra con las cantidades faltantes
            $item['detalle_id']= $entidad->detalle_id;
            $item['cantidad']= $reorden-$cantidad;
            $items[0]= $item;
            PreordenCompra::generarPreordenControlStock($items);
            return ControlStock::MINIMO;
        }
        if ($cantidad > $minimo && $cantidad <= $reorden) {
            $item['detalle_id']= $entidad->detalle_id;
            $item['cantidad']= $reorden-$cantidad;
            $items[0]= $item;
            PreordenCompra::generarPreordenControlStock($items);
            return ControlStock::REORDEN;
        }
        if ($cantidad > $reorden) {
            return ControlStock::SUFICIENTE;
        }
    }

    public static function actualizarEstado($detalle_id, $sucursal_id, $cliente_id){
        $control_stock = ControlStock::where('detalle_id', $detalle_id)
            ->where('sucursal_id', $sucursal_id)
            ->where('cliente_id', $cliente_id)->first();

        if ($control_stock) {
            $control_stock->update([
                'estado' => self::calcularEstado($control_stock, ControlStock::controlExistencias($detalle_id, $sucursal_id, $cliente_id), $control_stock->minimo, $control_stock->reorden)
            ]);
        }
    }

    /**
     * Relacion uno a muchos (inversa)
     * Obtener el detalle de producto al que pertenece el control de stock
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }
    /**
     * Relacion uno a muchos (inversa)
     * Obtener la sucursal al que pertenece el control de stock
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Obtener el cliente al que pertenece el id de detalle que se controla el stock
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
