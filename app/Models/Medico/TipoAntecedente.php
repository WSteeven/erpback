<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\TipoAntecedente
 *
 * @property int $id
 * @property string $nombre
 * @property string $genero
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|TipoAntecedente acceptRequest(?array $request = null)
 * @method static Builder|TipoAntecedente filter(?array $request = null)
 * @method static Builder|TipoAntecedente ignoreRequest(?array $request = null)
 * @method static Builder|TipoAntecedente newModelQuery()
 * @method static Builder|TipoAntecedente newQuery()
 * @method static Builder|TipoAntecedente query()
 * @method static Builder|TipoAntecedente setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|TipoAntecedente setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|TipoAntecedente setLoadInjectedDetection($load_default_detection)
 * @method static Builder|TipoAntecedente whereCreatedAt($value)
 * @method static Builder|TipoAntecedente whereGenero($value)
 * @method static Builder|TipoAntecedente whereId($value)
 * @method static Builder|TipoAntecedente whereNombre($value)
 * @method static Builder|TipoAntecedente whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TipoAntecedente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;
    const MASCULINO = 'MASCULINO';
    const FEMENINO = 'FEMENINO';
    // protected $table = 'med_tipos_antecedentes';
    protected $table = 'med_tipos_antecedentes'; // examenes_organos_reproductivos';
    protected $fillable = [
        'nombre',
        'genero'
    ];

    private static array $whiteListFilter = ['*'];
}
