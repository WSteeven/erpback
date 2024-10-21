<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Movimiento
 *
 * @property int $id
 * @property int $cantidad
 * @property float $precio_unitario
 * @property int $saldo
 * @property int $bodeguero_id
 * @property int $inventario_id
 * @property int $movimientable_id
 * @property string $movimientable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Empleado|null $bodeguero
 * @property-read Model|\Eloquent $movimientable
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento query()
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento whereBodegueroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento whereInventarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento whereMovimientableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento whereMovimientableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento whereSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movimiento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Movimiento extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;

    protected $table = "movimientos";
    protected $fillable=[
        // 'inventario_id',
        // 'detalle_producto_transaccion_id',
        'cantidad',
        'precio_unitario',
        'saldo',
        'bodeguero_id',
        'inventario_id',
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
    /**
     * Relacion uno a muchos inversa.
     * 
     */
    public function bodeguero(){
        return $this->belongsTo(Empleado::class);
    }
     /**
     * Relacion polimorfica
     */
    public function movimientable(){
        return $this->morphTo();
    }
    /**
     * Obtener el id en el inventario al que pertenecen los movimientos 
     */
    /* public function inventarios()
    {
        return $this->belongsTo(Inventario::class);
    } */


    /**
     * Relación uno a muchos (inversa).
     * Uno o varios movimientos pertenecen a un detalle
     */
    /* public function detalle(){
        return $this->belongsTo(DetalleProductoTransaccion::class);
    } */

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
