<?php

namespace App\Models\ComprasProveedores;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ComprasProveedores\OfertaProveedor
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComprasProveedores\CategoriaOfertaProveedor> $categorias
 * @property-read int|null $categorias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Proveedor> $servicios_ofertados
 * @property-read int|null $servicios_ofertados_count
 * @method static \Illuminate\Database\Eloquent\Builder|OfertaProveedor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfertaProveedor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfertaProveedor query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfertaProveedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfertaProveedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfertaProveedor whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfertaProveedor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
