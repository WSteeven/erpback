<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Producto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "productos";

    protected $fillable = ["nombre", "categoria_id"];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    /* Un nombre de producto es como una categoria. Ejm: Laptop
        DELL i5 ...
        Lenovo modelo xyz ...
        ... etc.
    */
    public function detalles_productos()
    {
        return $this->hasMany(DetallesProducto::class);
    }

    //Un producto tiene varias imagenes
    public function imagenes(){
        return $this->hasMany(ImagenesProducto::class);
    }

    //Un producto tiene varios codigos de cliente
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class);
    }


    /**
     * Uno o varios productos pertenecen a una categoría
     */
    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }
}
