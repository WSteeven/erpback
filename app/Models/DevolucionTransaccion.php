<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevolucionTransaccion extends Model
{
    use HasFactory;
    protected $table = 'devoluciones_transacciones';
    protected $fillable = [
        'detalle_producto_transaccion_id',
        'cantidad',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */


    /**
     * Relación uno a muchos(inversa).
     * Una o más devoluciones se hacen para un item de un traspaso.
     */
    public function detalleProductoTransaccion()
    {
        return $this->belongsTo(detalleProductoTransaccion::class);
    }
}
