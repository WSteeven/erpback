<?php

namespace App\Models\ComprasProveedores;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertaProveedor extends Model
{
    use HasFactory;
    public $table = 'ofertas_proveedores';
    public $fillable = ['nombre'];
   
   
    //TIPOS DE OFERTAS 
    const BIENES ='BIENES';
    const SERVICIOS='SERVICIOS';

    public function servicios_ofertados()
    {
        return $this->belongsToMany(Proveedor::class, 'detalle_oferta_proveedor', 'oferta_id', 'proveedor_id')
            ->withTimestamps();
    }

    public function categorias()
    {
        return $this->hasMany(CategoriaOfertaProveedor::class);
    }
}
