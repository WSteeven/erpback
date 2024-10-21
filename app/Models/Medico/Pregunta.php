<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Scope;

/**
 * App\Models\Medico\Pregunta
 *
 * @property int $id
 * @property string $codigo
 * @property string $pregunta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\Cuestionario> $cuestionario
 * @property-read int|null $cuestionario_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta wherePregunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pregunta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pregunta extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_preguntas';
    protected $fillable = [
        'codigo',
        'pregunta',
    ];
    private static $whiteListFilter = ['*'];

    public function cuestionario()
    {
        return $this->hasMany(Cuestionario::class, 'pregunta_id', 'id')->with('respuesta','respuestasCuestionariosEmpleados');
    }
} // 47
