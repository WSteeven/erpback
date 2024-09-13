<?php

namespace App\Models\ComprasProveedores;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComprasProveedores\NovedadOrdenCompra
 *
 * @property int $id
 * @property string $fecha_hora
 * @property string $actividad
 * @property string|null $observacion
 * @property string|null $fotografia
 * @property int|null $orden_compra_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra query()
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra whereActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra whereFotografia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra whereOrdenCompraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NovedadOrdenCompra whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NovedadOrdenCompra extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'cmp_novedades_ordenes_compras';
    protected $fillable = ['fecha_hora', 'actividad', 'observacion', 'fotografia', 'orden_compra_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    
}
