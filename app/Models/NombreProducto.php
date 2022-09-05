<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class NombreProducto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "nombres_de_productos";

    protected $fillable = ["nombre"];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    /* Un nombre de producto es como una categoria. Ejm: Laptop
        DELL i5 ...
        Lenovo modelo xyz ...
        ... etc.
    */
    public function productos()
    {
        return $this->hasMany(Producto::class);
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
}
