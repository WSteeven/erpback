<?php

namespace App\Models\Vehiculos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\Vehiculos\Tanqueo
 *
 * @property int $id
 * @property int $vehiculo_id
 * @property int $solicitante_id
 * @property string $fecha_hora
 * @property int $km_tanqueo
 * @property string|null $imagen_comprobante
 * @property string|null $imagen_tablero
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float $monto
 * @property int|null $combustible_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Vehiculos\Combustible|null $combustible
 * @property-read Empleado|null $solicitante
 * @property-read \App\Models\Vehiculos\Vehiculo|null $vehiculo
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereCombustibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereImagenComprobante($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereImagenTablero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereKmTanqueo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereMonto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tanqueo whereVehiculoId($value)
 * @mixin \Eloquent
 */
class Tanqueo extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use Filterable;
    use AuditableModel;

    protected $table = 'veh_tanqueos';
    protected $fillable = [
        'vehiculo_id',
        'solicitante_id',
        'fecha_hora',
        'km_tanqueo',
        'monto',
        'combustible_id',
        'imagen_comprobante',
        'imagen_tablero'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /** Se definen las constantes del tipo de reporte en la seccion de reporte de combustibles */
    const TIPO_RPT_COMBUSTIBLE = 'COMBUSTIBLE';
    const TIPO_RPT_VEHICULO = 'VEHICULO';

    /**
     * Relación uno a muchos (inversa).
     */
    public function combustible()
    {
        return $this->belongsTo(Combustible::class);
    }
    /**
     * Relación uno a muchos
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
}
