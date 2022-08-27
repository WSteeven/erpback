<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosProductos extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = "movimientos_de_productos";
    protected $fillable=[
        'producto_id',
        'cantidad',
        'precio_unitario',
        'precio_total',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}
