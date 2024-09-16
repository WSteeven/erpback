<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\TipoAntecedenteFamiliar
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAntecedenteFamiliar whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoAntecedenteFamiliar extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    const ENFERMEDAD_CARDIO_VASCULAR = 1;
    const ENFERMEDAD_METABOLICA = 2;
    const ENFERMEDAD_NEUROLOGICA = 3;
    const ONCOLOGICA = 4;
    const ENFERMEDAD_INFECIOSA = 5;
    const ENFERMEDAD_HEREDITARIA_CONGENITA = 6;
    const DISCAPACIDADES = 7;

    protected $table = 'med_tipos_antecedentes_familiares';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];
}
