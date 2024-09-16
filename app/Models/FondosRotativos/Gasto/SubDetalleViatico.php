<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\FondosRotativos\Usuario\Estatus;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Gasto\SubDetalleViatico
 *
 * @method static where(string $string, mixed $subdetalle)
 * @property int $id
 * @property int $id_detalle_viatico
 * @property string $descripcion
 * @property string $autorizacion
 * @property int $id_estatus
 * @property bool $tiene_factura
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\FondosRotativos\Gasto\DetalleViatico|null $detalle
 * @property-read Estatus|null $estatus
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FondosRotativos\Gasto\Gasto> $gastos
 * @property-read int|null $gastos_count
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico whereAutorizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico whereIdDetalleViatico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico whereIdEstatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico whereTieneFactura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubDetalleViatico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubDetalleViatico extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'sub_detalle_viatico';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_detalle_viatico',
        'descripcion',
        'autorizacion',
        'id_estatus',
        'transcriptor',
        'fecha_trans',
        'tiene_factura'
    ];
    protected $casts = [
        'tiene_factura' => 'boolean',
    ];
    private static $whiteListFilter = [
        'descripcion',
    ];
    public function detalle()
    {
        return $this->hasOne(DetalleViatico::class, 'id', 'id_detalle_viatico');
    }
    public function estatus()
    {
        return $this->hasOne(Estatus::class, 'id', 'id_estatus');
    }
    public function gastos()
    {
        return $this->belongsToMany(Gasto::class, 'subdetalle_gastos', 'detalle', 'id');
    }
}
