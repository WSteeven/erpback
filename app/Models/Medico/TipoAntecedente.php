<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\TipoAntecedente
 *
 * @property int $id
 * @property string $nombre
 * @property string $genero
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente whereGenero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedente whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = ['*'];
}
