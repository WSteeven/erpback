<?php

namespace App\Models;

use App\Models\FondosRotativos\Gasto\Gasto;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ItemDetallePreingresoMaterial
 *
 * @property int $id
 * @property int $preingreso_id
 * @property int $detalle_id
 * @property string $descripcion
 * @property string|null $serial
 * @property int $cantidad
 * @property int|null $punta_inicial
 * @property int|null $punta_final
 * @property int $unidad_medida_id
 * @property int|null $condicion_id
 * @property string|null $fotografia
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Condicion|null $condicion
 * @property-read \App\Models\DetalleProducto|null $detalle
 * @property-read \App\Models\UnidadMedida|null $unidadMedida
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereCondicionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereDetalleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereFotografia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial wherePreingresoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial wherePuntaFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial wherePuntaInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreingresoMaterial whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ItemDetallePreingresoMaterial extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable, UppercaseValuesTrait;

    protected $table = 'item_detalle_preingreso_material';
    protected $fillable = [
        'preingreso_id',
        'detalle_id',
        'descripcion',
        'cantidad',
        'serial',
        'punta_inicial',
        'punta_final',
        'unidad_medida_id',
        'condicion_id',
        'fotografia',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];


    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
    
    public function condicion()
    {
        return $this->belongsTo(Condicion::class);
    }
}
