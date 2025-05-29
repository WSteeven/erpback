<?php

namespace App\Models\Ventas;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Ventas\ProductoVenta
 *
 * @property int $id
 * @property string $nombre
 * @property string $bundle_id
 * @property string $precio
 * @property int $plan_id
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Plan|null $plan
 * @method static Builder|ProductoVenta acceptRequest(?array $request = null)
 * @method static Builder|ProductoVenta filter(?array $request = null)
 * @method static Builder|ProductoVenta ignoreRequest(?array $request = null)
 * @method static Builder|ProductoVenta newModelQuery()
 * @method static Builder|ProductoVenta newQuery()
 * @method static Builder|ProductoVenta query()
 * @method static Builder|ProductoVenta setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ProductoVenta setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ProductoVenta setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ProductoVenta whereActivo($value)
 * @method static Builder|ProductoVenta whereBundleId($value)
 * @method static Builder|ProductoVenta whereCreatedAt($value)
 * @method static Builder|ProductoVenta whereId($value)
 * @method static Builder|ProductoVenta whereNombre($value)
 * @method static Builder|ProductoVenta wherePlanId($value)
 * @method static Builder|ProductoVenta wherePrecio($value)
 * @method static Builder|ProductoVenta whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProductoVenta extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_productos_ventas';
    protected $fillable = ['nombre','nombre_corto', 'bundle_id', 'precio', 'plan_id', 'activo'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = [
        '*',
    ];
    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }
}
