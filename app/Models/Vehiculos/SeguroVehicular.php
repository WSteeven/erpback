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
 * App\Models\Vehiculos\SeguroVehicular
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $num_poliza
 * @property string $fecha_caducidad
 * @property bool $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Vehiculos\Vehiculo|null $vehiculo
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular whereFechaCaducidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular whereNumPoliza($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguroVehicular whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeguroVehicular extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'veh_seguros_vehiculares';
    protected $fillable = [
        'nombre',
        'num_poliza',
        'fecha_caducidad',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean'
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación uno a uno.
     * Un Seguro pertenece a un vehículo a su debido momento.
     */
    public function vehiculo()
    {
        return $this->hasOne(Vehiculo::class, 'seguro_id');
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Un seguro vehicular puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
}
