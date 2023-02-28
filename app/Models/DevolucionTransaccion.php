<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DevolucionTransaccion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
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
        return $this->belongsTo(DetalleProductoTransaccion::class);
    }
}
