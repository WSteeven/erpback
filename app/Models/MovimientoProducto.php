<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class MovimientoProducto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "movimientos_productos";
    protected $fillable=[
        'inventario_id',
        'detalle_producto_transaccion_id',
        'cantidad',
        'precio_unitario',
        'saldo',
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
