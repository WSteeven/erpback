<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosProductos extends Model
{
    use HasFactory;
    protected $table = "movimientos_de_productos";
    protected $fillable=[
        'producto_id',
        'cantidad',
        'precio_unitario',
        'precio_total',
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}
