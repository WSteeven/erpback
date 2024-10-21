<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Motivo
 *
 * @method static where(string $string, $id)
 * @property int $id
 * @property string $nombre
 * @property int $tipo_transaccion_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\TipoTransaccion|null $tipoTransaccion
 * @property-read \App\Models\TransaccionBodega|null $transaccion
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo where()
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereTipoTransaccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motivo whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = ['*'];

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
}
