<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class Movimiento extends Model implements Auditable
{
    use HasFactory;
    use Auditable;
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
