<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Ventas\NovedadVenta
 *
 * @property int $id
 * @property string $fecha_hora
 * @property string $actividad
 * @property string|null $observacion
 * @property string|null $fotografia
 * @property int|null $venta_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Ventas\Venta|null $venta
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta query()
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta whereActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta whereFotografia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadVenta whereVentaId($value)
 * @mixin \Eloquent
 */
class NovedadVenta extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'ventas_novedades_ventas';
    protected $fillable = ['fecha_hora', 'actividad', 'observacion', 'fotografia', 'venta_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    public function venta(){
        return $this->belongsTo(Venta::class);
    }
}
