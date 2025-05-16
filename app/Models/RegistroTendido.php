<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RegistroTendido
 *
 * @property int $id
 * @property string|null $propietario_americano
 * @property int $numero_elemento
 * @property string $codigo_elemento
 * @property int $progresiva_entrada
 * @property int $progresiva_salida
 * @property float $coordenada_del_elemento_latitud
 * @property float $coordenada_del_elemento_longitud
 * @property float|null $coordenada_cruce_americano_longitud
 * @property float|null $coordenada_cruce_americano_latitud
 * @property float|null $coordenada_poste_anclaje1_longitud
 * @property float|null $coordenada_poste_anclaje1_latitud
 * @property float|null $coordenada_poste_anclaje2_longitud
 * @property float|null $coordenada_poste_anclaje2_latitud
 * @property string $estado_elemento
 * @property string $tipo_elemento
 * @property string $propietario_elemento
 * @property int|null $cantidad_transformadores
 * @property bool $tiene_americano
 * @property int|null $cantidad_retenidas
 * @property bool $instalo_manga
 * @property int|null $cantidad_reserva
 * @property string|null $tension
 * @property string|null $observaciones
 * @property array $materiales_ocupados
 * @property string $imagen_elemento
 * @property string|null $imagen_cruce_americano
 * @property string|null $imagen_poste_anclaje1
 * @property string|null $imagen_poste_anclaje2
 * @property int $tendido_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCantidadReserva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCantidadRetenidas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCantidadTransformadores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCodigoElemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCoordenadaCruceAmericanoLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCoordenadaCruceAmericanoLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCoordenadaDelElementoLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCoordenadaDelElementoLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCoordenadaPosteAnclaje1Latitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCoordenadaPosteAnclaje1Longitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCoordenadaPosteAnclaje2Latitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCoordenadaPosteAnclaje2Longitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereEstadoElemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereImagenCruceAmericano($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereImagenElemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereImagenPosteAnclaje1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereImagenPosteAnclaje2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereInstaloManga($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereMaterialesOcupados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereNumeroElemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereProgresivaEntrada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereProgresivaSalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido wherePropietarioAmericano($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido wherePropietarioElemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereTendidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereTension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereTieneAmericano($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereTipoElemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroTendido whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        'cantidad_transformadores',
        'tiene_americano',
        'cantidad_retenidas',
        'instalo_manga',
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
        'tiene_americano' => 'boolean',
        'instalo_manga' => 'boolean',
        'materiales_ocupados' => 'json',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
}
