<?php

namespace App\Models\Intranet;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Intranet\TipoEvento
 *
 * @property int $id
 * @property string $nombre
 * @property int $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoEvento extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'intra_tipos_eventos';
    protected $fillable = [
        'nombre',
        'activo'
    ];

    private static array $whiteListFilter = [
        '*',
    ];
}
