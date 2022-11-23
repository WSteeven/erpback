<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
