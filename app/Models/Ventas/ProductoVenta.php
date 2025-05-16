<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Ventas\ProductoVenta
 *
 * @property int $id
 * @property string $nombre
 * @property string $bundle_id
 * @property string $precio
 * @property int $plan_id
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Ventas\Plan|null $plan
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta whereBundleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoVenta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductoVenta extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_productos_ventas';
    protected $fillable = ['nombre', 'bundle_id', 'precio', 'plan_id', 'activo'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];
    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }
}
