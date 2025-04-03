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


/**
 * App\Models\ControlStock
 *
 * @property int $id
 * @property int $detalle_id
 * @property int $sucursal_id
 * @property int $cliente_id
 * @property int|null $minimo
 * @property int|null $reorden
 * @property int|null $maximo
 * @property string $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Cliente $cliente
 * @property-read \App\Models\DetalleProducto $detalle
 * @property-read \App\Models\Sucursal $sucursal
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock query()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereDetalleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereMaximo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereMinimo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereReorden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereSucursalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlStock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
