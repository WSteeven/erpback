<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ImagenProducto
 *
 * @property int $id
 * @property string $url
 * @property int $detalle_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\DetalleProducto|null $detalle
 * @method static \Database\Factories\ImagenProductoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ImagenProducto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImagenProducto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImagenProducto query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImagenProducto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImagenProducto whereDetalleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImagenProducto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImagenProducto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImagenProducto whereUrl($value)
 * @mixin \Eloquent
 */
class ImagenProducto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "imagenes_productos";
    protected $fillable = ["url",'detalle_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];



    /**
     * Relacion uno a muchos (inversa).
     * Una o más imagenes pertenecen a un solo detalle de producto.
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }


}
