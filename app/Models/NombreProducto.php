<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NombreProducto extends Model
{
    use HasFactory;
    protected $table = "nombres_de_productos";

    protected $fillable = ["nombre"];


    /* Un nombre de producto es como una categoria. Ejm: Laptop
        DELL i5 ...
        Lenovo modelo xyz ...
        ... etc.
    */
    /* public function productos()
    {
        return $this->hasMany(Producto::class);
    } */
}
