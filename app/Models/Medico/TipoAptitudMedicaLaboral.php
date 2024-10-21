<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\TipoAptitudMedicaLaboral
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitudMedicaLaboral whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoAptitudMedicaLaboral extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_tipos_aptitudes_medica_laborales';
    protected $fillable = [
        'nombre',
    ];

    private static $whiteListFilter = ['*'];
}

