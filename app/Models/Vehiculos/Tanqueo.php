<?php

namespace App\Models\Vehiculos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;


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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property float $monto
 * @property int|null $combustible_id
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Combustible|null $combustible
 * @property-read Empleado|null $solicitante
 * @property-read Vehiculo|null $vehiculo
 * @method static Builder|Tanqueo acceptRequest(?array $request = null)
 * @method static Builder|Tanqueo filter(?array $request = null)
 * @method static Builder|Tanqueo ignoreRequest(?array $request = null)
 * @method static Builder|Tanqueo newModelQuery()
 * @method static Builder|Tanqueo newQuery()
 * @method static Builder|Tanqueo query()
 * @method static Builder|Tanqueo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Tanqueo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Tanqueo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Tanqueo whereCombustibleId($value)
 * @method static Builder|Tanqueo whereCreatedAt($value)
 * @method static Builder|Tanqueo whereFechaHora($value)
 * @method static Builder|Tanqueo whereId($value)
 * @method static Builder|Tanqueo whereImagenComprobante($value)
 * @method static Builder|Tanqueo whereImagenTablero($value)
 * @method static Builder|Tanqueo whereKmTanqueo($value)
 * @method static Builder|Tanqueo whereMonto($value)
 * @method static Builder|Tanqueo whereSolicitanteId($value)
 * @method static Builder|Tanqueo whereUpdatedAt($value)
 * @method static Builder|Tanqueo whereVehiculoId($value)
 * @mixin Eloquent
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
        'bitacora_id',
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

    private static array $whiteListFilter = [
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

    public function bitacora()
    {
        return $this->belongsTo(BitacoraVehicular::class, 'bitacora_id', 'id');
    }
}
