<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condicion extends Model
{
    use HasFactory;
    protected $table = 'condiciones_de_productos';
    protected $fillable = ['nombre'];

    public function productoPercha()
    {
        return $this->belongsTo(ProductosEnPercha::class);
    }
}
