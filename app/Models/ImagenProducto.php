<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
