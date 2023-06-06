<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertaProveedor extends Model
{
    use HasFactory;
    public $table = 'ofertas_proveedores';
    public $fillable = ['nombre'];
    
    
    public function servicios_ofertados(){
        return $this->belongsToMany(Proveedor::class, 'detalle_oferta_proveedor','oferta_id','proveedor_id')
        ->withTimestamps();
    }
}
