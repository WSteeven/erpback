<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoCliente extends Model
{
    use HasFactory, UppercaseValuesTrait;

    protected $table = "codigo_cliente";
    protected $fillable = ['propietario_id', 'producto_id', 'codigo'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

}
