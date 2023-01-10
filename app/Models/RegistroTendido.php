<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroTendido extends Model
{
    use HasFactory;
    protected $table = 'registro_tendidos';
    protected $fillable = [
        'tipo_elemento',
        'propietario_elemento',
        'propietario_americano',
        'numero_elemento',
        'codigo_elemento',
        'progresiva_entrada',
        'progresiva_salida',
        'coordenada_del_elemento_latitud',
        'coordenada_del_elemento_longitud',
        'coordenada_cruce_americano_longitud',
        'coordenada_cruce_americano_latitud',
        'coordenada_poste_anclaje1_longitud',
        'coordenada_poste_anclaje1_latitud',
        'coordenada_poste_anclaje2_longitud',
        'coordenada_poste_anclaje2_latitud',
        'estado_elemento',
        'tiene_transformador',
        'cantidad_transformadores',
        'tiene_americano',
        'tiene_retenidas',
        'cantidad_retenidas',
        'instalo_manga',
        'instalo_reserva',
        'cantidad_reserva',
        'observaciones',
        'tension',
        'materiales_ocupados',
        'tendido_id',
        'imagen_elemento',
        'imagen_cruce_americano',
        'imagen_poste_anclaje1',
        'imagen_poste_anclaje2',
    ];

    protected $casts = [
        'tiene_transformador' => 'boolean',
        'tiene_americano' => 'boolean',
        'tiene_retenidas' => 'boolean',
        'instalo_manga' => 'boolean',
        'instalo_reserva' => 'boolean',
        'materiales_ocupados' => 'json',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
}
