<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Ventas\UmbralVenta
 *
 * @property int $id
 * @property int $cantidad_ventas
 * @property int|null $vendedor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Ventas\Vendedor|null $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta query()
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta whereCantidadVentas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralVenta whereVendedorId($value)
 * @mixin \Eloquent
 */
class UmbralVenta extends Model implements Auditable
{
   use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_umbrales_ventas';
    protected $fillable =['cantidad_ventas','vendedor_id'];
    private static $whiteListFilter = [
        '*',
    ];
    public function vendedor(){
        return $this->hasOne(Vendedor::class,'id','vendedor_id')->with('empleado');
    }
}
