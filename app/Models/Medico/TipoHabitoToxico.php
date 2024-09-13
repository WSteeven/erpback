<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\TipoHabitoToxico
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoHabitoToxico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoHabitoToxico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_tipos_habitos_toxicos';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];
}
