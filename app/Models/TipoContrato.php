<?php

namespace App\Models;

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
 * App\Models\TipoContrato
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|TipoContrato acceptRequest(?array $request = null)
 * @method static Builder|TipoContrato filter(?array $request = null)
 * @method static Builder|TipoContrato ignoreRequest(?array $request = null)
 * @method static Builder|TipoContrato newModelQuery()
 * @method static Builder|TipoContrato newQuery()
 * @method static Builder|TipoContrato query()
 * @method static Builder|TipoContrato setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|TipoContrato setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|TipoContrato setLoadInjectedDetection($load_default_detection)
 * @method static Builder|TipoContrato whereCreatedAt($value)
 * @method static Builder|TipoContrato whereId($value)
 * @method static Builder|TipoContrato whereNombre($value)
 * @method static Builder|TipoContrato whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = [
        'id',
        'nombre',
    ];
}
