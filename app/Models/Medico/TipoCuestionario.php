<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\TipoCuestionario
 *
 * @property int $id
 * @property string $titulo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoCuestionario whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoCuestionario extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_tipos_cuestionarios';
    protected $fillable = [
        'titulo',
    ];
    private static $whiteListFilter = ['*'];

    // Tipos de cuestionarios
    const CUESTIONARIO_PSICOSOCIAL = 1;
    const CUESTIONARIO_DIAGNOSTICO_CONSUMO_DE_DROGAS = 2;
}
