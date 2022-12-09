<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DetalleInventarioTraspaso extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table='detalle_inventario_traspaso';
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
        'traspaso_id',
        'inventario_id',
        'cantidad',
        'devolucion',
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
     * Relacion uno a muchos polimorfica
     */
    public function movimientos(){
        return $this->morphMany('App\Models\MovimientoProducto', 'movimientable');
    }

    /**
     * Relación uno a muchos.
     * Un detalle de un traspaso tiene una o varias devoluciones
     */
    public function devoluciones(){
        return $this->hasMany(DevolucionTraspaso::class);
    }
}
