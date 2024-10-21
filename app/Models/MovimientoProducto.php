<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\MovimientoProducto
 *
 * @property int $id
 * @property int $inventario_id
 * @property int $detalle_producto_transaccion_id
 * @property int $cantidad
 * @property float|null $precio_unitario
 * @property int $saldo
 * @property string $tipo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\DetalleProductoTransaccion|null $detalle
 * @property-read \App\Models\Inventario|null $inventarios
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto query()
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto whereDetalleProductoTransaccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto whereInventarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto whereSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovimientoProducto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MovimientoProducto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "movimientos_productos";
    // protected $table = "movimientos";
    protected $fillable=[
        // 'inventario_id',
        // 'detalle_producto_transaccion_id',
        'cantidad',
        'precio_unitario',
        'saldo',
        'detalle_producto_transaccion_id',
        'inventario_id',
        'tipo',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    
    /* *
     * Obtener el id en el inventario al que pertenecen los movimientos 
     */
    public function inventarios()
    {
        return $this->belongsTo(Inventario::class);
    }


    /**
     * Relación uno a muchos (inversa).
     * Uno o varios movimientos pertenecen a un detalle
     */
    public function detalle(){
        return $this->belongsTo(DetalleProductoTransaccion::class);
    }

    /**
     * Obtener la transaccion a la que pertenecen los movimientos
     */
    /* public function transacciones(){
        return $this->belongsTo(TransaccionBodega::class);
    } */

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
}
