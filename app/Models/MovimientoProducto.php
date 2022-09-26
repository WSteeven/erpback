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
        'transaccion_id',
        'cantidad',
        'precio_unitario',
        'saldo',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Obtener el id en el inventario al que pertenecen los movimientos 
     */
    public function inventarios()
    {
        return $this->belongsTo(Inventario::class);
    }

    /**
     * Obtener la transaccion a la que pertenecen los movimientos
     */
    public function transacciones(){
        return $this->belongsTo(TransaccionBodega::class);
    }
}
