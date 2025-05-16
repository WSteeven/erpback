<?php

namespace App\Models\ComprasProveedores;

use App\Models\Proveedor;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ComprasProveedores\OfertaProveedor
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, CategoriaOfertaProveedor> $categorias
 * @property-read int|null $categorias_count
 * @property-read Collection<int, Proveedor> $servicios_ofertados
 * @property-read int|null $servicios_ofertados_count
 * @method static Builder|OfertaProveedor newModelQuery()
 * @method static Builder|OfertaProveedor newQuery()
 * @method static Builder|OfertaProveedor query()
 * @method static Builder|OfertaProveedor whereCreatedAt($value)
 * @method static Builder|OfertaProveedor whereId($value)
 * @method static Builder|OfertaProveedor whereNombre($value)
 * @method static Builder|OfertaProveedor whereUpdatedAt($value)
 * @mixin Eloquent
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
