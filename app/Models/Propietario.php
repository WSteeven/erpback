<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = "propietarios";
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];



    /* Un propietario tiene varios productos en perchas */
    /* public function productosPercha()
    {
        return $this->hasMany(ProductosEnPercha::class);
    } */
}
