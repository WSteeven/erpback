<?php

namespace App\Models\Vehiculos;

use App\Models\Archivo;
use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use Closure;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Vehiculos\OrdenReparacion
 *
 * @method static filter()
 * @method static where(Closure $param)
 * @method static create(mixed $datos)
 * @property int $id
 * @property int|null $solicitante_id
 * @property int|null $vehiculo_id
 * @property int|null $autorizador_id
 * @property int|null $autorizacion_id
 * @property string|null $servicios
 * @property string|null $observacion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Autorizacion|null $autorizacion
 * @property-read Empleado|null $autorizador
 * @property-read Notificacion|null $latestNotificacion
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Empleado|null $solicitante
 * @property-read Vehiculo|null $vehiculo
 * @method static Builder|OrdenReparacion acceptRequest(?array $request = null)
 * @method static Builder|OrdenReparacion ignoreRequest(?array $request = null)
 * @method static Builder|OrdenReparacion newModelQuery()
 * @method static Builder|OrdenReparacion newQuery()
 * @method static Builder|OrdenReparacion query()
 * @method static Builder|OrdenReparacion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|OrdenReparacion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|OrdenReparacion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|OrdenReparacion whereAutorizacionId($value)
 * @method static Builder|OrdenReparacion whereAutorizadorId($value)
 * @method static Builder|OrdenReparacion whereCreatedAt($value)
 * @method static Builder|OrdenReparacion whereId($value)
 * @method static Builder|OrdenReparacion whereObservacion($value)
 * @method static Builder|OrdenReparacion whereServicios($value)
 * @method static Builder|OrdenReparacion whereSolicitanteId($value)
 * @method static Builder|OrdenReparacion whereUpdatedAt($value)
 * @method static Builder|OrdenReparacion whereVehiculoId($value)
 * @mixin Eloquent
 */
class OrdenReparacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'veh_ordenes_reparaciones';
    protected $fillable = [
        'solicitante_id',
        'autorizador_id',
        'autorizacion_id',
        'vehiculo_id',
        'servicios',
        'observacion',
        'fecha',
        'valor_reparacion',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    private static array $whiteListFilter = ['*'];



    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function kmRealizado($vehiculo_id, $fecha)
    {
        $bitacora = BitacoraVehicular::where('vehiculo_id', $vehiculo_id)->where('created_at', '>=', $fecha)->first();
        if ($bitacora)
            return $bitacora->km_inicial;
        else {
            $bitacora = BitacoraVehicular::where('vehiculo_id', $vehiculo_id)->where('created_at', '<=', $fecha)->orderBy('created_at', 'desc')->first();
            return $bitacora?->km_inicial;
        }
    }
}
