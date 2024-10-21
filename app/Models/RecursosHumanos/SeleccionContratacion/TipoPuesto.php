<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

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
 * App\Models\RecursosHumanos\SeleccionContratacion\TipoPuesto
 *
 * @method static create(mixed $validated)
 * @method static ignoreRequest(string[] $array)
 * @method static upsert(array[] $array, string[] $uniqueBy, string[] $update)
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|TipoPuesto acceptRequest(?array $request = null)
 * @method static Builder|TipoPuesto filter(?array $request = null)
 * @method static Builder|TipoPuesto newModelQuery()
 * @method static Builder|TipoPuesto newQuery()
 * @method static Builder|TipoPuesto query()
 * @method static Builder|TipoPuesto setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|TipoPuesto setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|TipoPuesto setLoadInjectedDetection($load_default_detection)
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|TipoPuesto whereActivo($value)
 * @method static Builder|TipoPuesto whereCreatedAt($value)
 * @method static Builder|TipoPuesto whereId($value)
 * @method static Builder|TipoPuesto whereNombre($value)
 * @method static Builder|TipoPuesto whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TipoPuesto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'rrhh_contratacion_tipos_puestos';
    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];
    private static array $whiteListFilter = [
        '*'
    ];
}
