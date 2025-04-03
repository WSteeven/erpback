<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VistaInventarioPercha
 *
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \App\Models\Condicion|null $condicion
 * @property-read \App\Models\DetalleProducto|null $detalle
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductoEnPercha> $productoPercha
 * @property-read int|null $producto_percha_count
 * @property-read \App\Models\Sucursal|null $sucursal
 * @method static \Illuminate\Database\Eloquent\Builder|VistaInventarioPercha newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VistaInventarioPercha newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VistaInventarioPercha query()
 * @mixin \Eloquent
 */
class VistaInventarioPercha extends Model
{
    use HasFactory;

    protected $table = 'view_inventario_percha';


    public function detalle(){
        return $this->belongsTo(DetalleProducto::class);
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function sucursal(){
        return $this->belongsTo(Sucursal::class);
    }
    public function condicion(){
        return $this->belongsTo(Condicion::class);
    }
    public function productoPercha(){
        return $this->hasMany(ProductoEnPercha::class);
    }


    

    public static function consultarItemsInventarioPercha(){
        return VistaInventarioPercha::all();
    }
}
