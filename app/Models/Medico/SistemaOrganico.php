<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\SistemaOrganico
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico query()
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SistemaOrganico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SistemaOrganico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    const PIEL_ANEXOS = 1;
    const ORGANOS_DE_LOS_SENTIDOS = 2;
    const RESPIRATORIO = 3;
    const CARDIOVASCULAR = 4;
    const DIGESTIVO = 5;
    const GENITO_URINARIO = 6;
    const MUSCULO_ESQUELETICO = 7;
    const ENDOCRINO = 8;
    const HEMOLINFATICO = 9;
    const NERVIOSO = 10;

    protected $table = 'med_sistemas_organicos';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];
}
