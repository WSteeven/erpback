<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
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
}
