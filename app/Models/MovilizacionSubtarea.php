<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\MovilizacionSubtarea
 *
 * @property int $id
 * @property string $motivo
 * @property string|null $fecha_hora_salida
 * @property string|null $fecha_hora_llegada
 * @property string $latitud
 * @property string $longitud
 * @property int $empleado_id
 * @property int $subtarea_id
 * @property string $latitud_llegada
 * @property string $longitud_llegada
 * @property int|null $coordinador_registrante_llegada
 * @property string $estado_subtarea_llegada
 * @property-read \App\Models\Empleado|null $coordinadorRegistranteLlegada
 * @property-read \App\Models\Empleado|null $empleado
 * @property-read \App\Models\Subtarea|null $subtarea
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereCoordinadorRegistranteLlegada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereEstadoSubtareaLlegada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereFechaHoraLlegada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereFechaHoraSalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereLatitudLlegada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereLongitudLlegada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovilizacionSubtarea whereSubtareaId($value)
 * @mixin \Eloquent
 */
class MovilizacionSubtarea extends Model
{
    use HasFactory, Filterable;

    // Motivos de movilizacion
    const IDA_A_TRABAJO = 'IDA';
    const REGRESO_DE_TRABAJO = 'REGRESO';

    public $timestamps = false;
    protected $table = "movilizacion_subtarea";
    protected $fillable = [
        'fecha_hora_salida',
        'fecha_hora_llegada',
        'motivo',
        'latitud',
        'longitud',
        'latitud_llegada',
        'longitud_llegada',
        'estado_subtarea_llegada',
        'coordinador_registrante_llegada',
        'empleado_id',
        'subtarea_id'
    ];

    private static $whiteListFilter = [
        '*'
    ];

    public function empleado() {
        return $this->belongsTo(Empleado::class);
    }

    public function subtarea() {
        return $this->belongsTo(Subtarea::class);
    }

    public function coordinadorRegistranteLlegada() {
        return $this->belongsTo(Empleado::class, 'coordinador_registrante_llegada', 'id');
    }
}
