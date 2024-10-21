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
 * App\Models\FondosRotativos\Gasto\DetalleViatico
 *
 * @method static where(string $string, mixed $detalle)
 * @property int $id
 * @property string $descripcion
 * @property string $autorizacion
 * @property int $id_estatus
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Estatus|null $estatus
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FondosRotativos\Gasto\Gasto> $gastos
 * @property-read int|null $gastos_count
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico whereAutorizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico whereIdEstatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleViatico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetalleViatico extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'detalle_viatico';
    protected $primaryKey = 'id';
    public const PEAJE =16;
    public const ENVIO_ENCOMIENDA =10;

    protected $fillable = [
        'descripcion',
        'autorizacion',
        'id_estatus',
    ];
    private static $whiteListFilter = [
        'descripcion',
    ];
    public function estatus()
    {
        return $this->hasOne(Estatus::class, 'id','id_estatus');
    }
    public function gastos(){
        return $this->hasMany(Gasto::class,'detalle','id');
    }
}
