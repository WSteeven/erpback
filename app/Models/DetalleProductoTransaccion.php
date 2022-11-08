<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class DetalleProductoTransaccion extends Pivot implements Auditable
{
    use HasFactory;
    use AuditableModel;
    protected $table = 'detalle_producto_transaccion';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'detalle_id',
        'transaccion_id',
        'cantidad_inicial',
        'cantidad_final',
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
     * Relacion uno a uno(inversa).
     * Un detalle_producto_transaccion pertenece a un detalle_producto.
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    /**
     * Relacion uno a uno(inversa).
     * Un detalle_producto_transaccion pertenece a una transaccion.
     */
    public function transaccion()
    {
        return $this->belongsTo(TransaccionBodega::class);
    }
}
