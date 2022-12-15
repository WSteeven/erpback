<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class DetalleProductoTransaccion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
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

    private static $whiteListFilter = ['*'];

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
        return $this->belongsTo(TransaccionBodega::class, 'transaccion_id', 'id');
    }

    /**
     * Relación uno a muchos.
     * Un detalle_producto_transaccion tiene varios movimientos
     */
    public function movimientos(){
        return $this->hasMany(MovimientoProducto::class);
    }

    /**
     * Relacion uno a muchos polimorfica
     */
    // public function movimientos(){
    //     // return $this->morphMany('App\Models\MovimientoProducto', 'movimientable');
    //     return $this->morphMany(MovimientoProducto::class, 'movimientable');
    // }
    /**
     * Relación uno a muchos.
     * Un detalle de una transaccion tiene una o varias devoluciones parciales
     */
    public function devoluciones(){
        return $this->hasMany(DevolucionTransaccion::class);
    }


}
