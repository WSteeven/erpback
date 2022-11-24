<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    use HasFactory;
    protected $table = "transferencias";
    protected $fillable = [
        'transaccion_id',
        'sucursal_destino_id',
        'atendida',
        'recibida',
        'devuelta',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const PENDIENTE = "PENDIENTE";
    const TRANSITO = "TRANSITO";
    const COMPLETADO = "COMPLETADO";
    
}
