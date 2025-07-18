<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Motivo
 *
 * @method static where(string $string, $id)
 * @property int $id
 * @property string $nombre
 * @property int $tipo_transaccion_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read TipoTransaccion|null $tipoTransaccion
 * @property-read TransaccionBodega|null $transaccion
 * @method static Builder|Motivo acceptRequest(?array $request = null)
 * @method static Builder|Motivo filter(?array $request = null)
 * @method static Builder|Motivo ignoreRequest(?array $request = null)
 * @method static Builder|Motivo newModelQuery()
 * @method static Builder|Motivo newQuery()
 * @method static Builder|Motivo query()
 * @method static Builder|Motivo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Motivo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Motivo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Motivo whereCreatedAt($value)
 * @method static Builder|Motivo whereId($value)
 * @method static Builder|Motivo whereNombre($value)
 * @method static Builder|Motivo whereTipoTransaccionId($value)
 * @method static Builder|Motivo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Motivo extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = 'motivos';
    protected $fillable = ['nombre', 'tipo_transaccion_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const COMPRA_PROVEEDOR = 'COMPRA A PROVEEDOR';

    private static array $whiteListFilter = ['*'];

    /**
     * Relacion uno a muchos (inversa)
     * Uno o varios subtipos pertenecen a un tipo de transaccion
     */
    public function tipoTransaccion()
    {
        return $this->belongsTo(TipoTransaccion::class);
    }

    public function transaccion()
    {
        return $this->hasOne(TransaccionBodega::class);
    }

    public static function obtenerMotivoPorNombre(string $nombre, int $tipo_transaccion_id){
        // $tipo_transaccion_id es 1 para INGRESO    Y 2 para EGRESO
        return Motivo::where('nombre',  $nombre)->where('tipo_transaccion_id', $tipo_transaccion_id)->first();
    }
}
