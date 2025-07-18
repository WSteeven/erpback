<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\FondosRotativos\Usuario\Estatus;
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
 * App\Models\FondosRotativos\Gasto\DetalleViatico
 *
 * @method static where(string $string, mixed $detalle)
 * @property int $id
 * @property string $descripcion
 * @property string $autorizacion
 * @property int $id_estatus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Estatus|null $estatus
 * @property-read Collection<int, Gasto> $gastos
 * @property-read int|null $gastos_count
 * @method static Builder|DetalleViatico acceptRequest(?array $request = null)
 * @method static Builder|DetalleViatico filter(?array $request = null)
 * @method static Builder|DetalleViatico ignoreRequest(?array $request = null)
 * @method static Builder|DetalleViatico newModelQuery()
 * @method static Builder|DetalleViatico newQuery()
 * @method static Builder|DetalleViatico query()
 * @method static Builder|DetalleViatico setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|DetalleViatico setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|DetalleViatico setLoadInjectedDetection($load_default_detection)
 * @method static Builder|DetalleViatico whereAutorizacion($value)
 * @method static Builder|DetalleViatico whereCreatedAt($value)
 * @method static Builder|DetalleViatico whereDescripcion($value)
 * @method static Builder|DetalleViatico whereId($value)
 * @method static Builder|DetalleViatico whereIdEstatus($value)
 * @method static Builder|DetalleViatico whereUpdatedAt($value)
 * @mixin Eloquent
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
    private static array $whiteListFilter = [
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
