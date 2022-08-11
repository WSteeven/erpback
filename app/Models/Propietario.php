<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory;
    protected $table = "propietarios";

    /* Un propietario tiene varios productos en perchas */
    public function productosPercha()
    {
        return $this->hasMany(ProductosEnPercha::class);
    }
}
