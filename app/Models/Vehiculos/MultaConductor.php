<?php

namespace App\Models\Vehiculos;

use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\MultaConductor
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property string $fecha_infraccion
 * @property string|null $fecha_pago
 * @property string|null $comentario
 * @property string|null $placa
 * @property float|null $puntos
 * @property float $total
 * @property bool $estado
 * @property bool $descontable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Vehiculos\Conductor|null $conductor
 * @property-read Notificacion|null $latestNotificacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor query()
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereComentario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereDescontable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereFechaInfraccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereFechaPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor wherePlaca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor wherePuntos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MultaConductor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MultaConductor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'veh_multas_conductores';
    protected $fillable = [
        'empleado_id',
        'fecha_infraccion',
        'placa',
        'puntos',
        'total',
        'estado',
        'fecha_pago',
        'comentario',
        'descontable',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado'=>'boolean',
        'descontable'=>'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa).
     * Una o varias multas pertenecen a un Conductor.
     */
    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'empleado_id');
    }
    
    /**
     * Relación polimorfica a una notificación.
     * Un pedido puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }
}
