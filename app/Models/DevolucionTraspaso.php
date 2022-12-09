<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevolucionTraspaso extends Model
{
    use HasFactory;

    protected $table = 'devolucion_traspasos';
    protected $fillable = [
        'detalle_inventario_traspaso_id',
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
    public function detalleInventarioTraspaso()
    {
        return $this->belongsTo(DetalleInventarioTraspaso::class);
    }
}
