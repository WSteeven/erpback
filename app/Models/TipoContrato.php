<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\TipoContrato
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoContrato whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoContrato extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'tipo_contrato';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
    ];
}
