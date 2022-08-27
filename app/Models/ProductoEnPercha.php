<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoEnPercha extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = "inventarios";
    const INVENTARIO = "INVENTARIO";
    const NODISPONIBLE = "NO DISPONIBLE";

    protected $fillable=[
        'producto_id',
        'condicion_id',
        'ubicacion_id',
        'propietario_id',
        'stock',
        'prestados',
        'estado',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


}
