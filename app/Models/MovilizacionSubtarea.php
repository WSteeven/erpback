<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @property-read Empleado|null $coordinadorRegistranteLlegada
 * @property-read Empleado|null $empleado
 * @property-read Subtarea|null $subtarea
 * @method static Builder|MovilizacionSubtarea acceptRequest(?array $request = null)
 * @method static Builder|MovilizacionSubtarea filter(?array $request = null)
 * @method static Builder|MovilizacionSubtarea ignoreRequest(?array $request = null)
 * @method static Builder|MovilizacionSubtarea newModelQuery()
 * @method static Builder|MovilizacionSubtarea newQuery()
 * @method static Builder|MovilizacionSubtarea query()
 * @method static Builder|MovilizacionSubtarea setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|MovilizacionSubtarea setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|MovilizacionSubtarea setLoadInjectedDetection($load_default_detection)
 * @method static Builder|MovilizacionSubtarea whereCoordinadorRegistranteLlegada($value)
 * @method static Builder|MovilizacionSubtarea whereEmpleadoId($value)
 * @method static Builder|MovilizacionSubtarea whereEstadoSubtareaLlegada($value)
 * @method static Builder|MovilizacionSubtarea whereFechaHoraLlegada($value)
 * @method static Builder|MovilizacionSubtarea whereFechaHoraSalida($value)
 * @method static Builder|MovilizacionSubtarea whereId($value)
 * @method static Builder|MovilizacionSubtarea whereLatitud($value)
 * @method static Builder|MovilizacionSubtarea whereLatitudLlegada($value)
 * @method static Builder|MovilizacionSubtarea whereLongitud($value)
 * @method static Builder|MovilizacionSubtarea whereLongitudLlegada($value)
 * @method static Builder|MovilizacionSubtarea whereMotivo($value)
 * @method static Builder|MovilizacionSubtarea whereSubtareaId($value)
 * @mixin Eloquent
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
