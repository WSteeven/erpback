<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Medico\CuestionarioPublico
 *
 * @property int $id
 * @property int $cuestionario_id
 * @property int $persona_id
 * @property string|null $respuesta_texto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\Cuestionario|null $cuestionario
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico query()
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico whereCuestionarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico wherePersonaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico whereRespuestaTexto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CuestionarioPublico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CuestionarioPublico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_cuestionarios_publicos'; // med_respuestas_cuestionarios_empleados
    protected $fillable = [
        'respuesta_texto',
        'cuestionario_id',
        'persona_id',
        // 'respuesta', // id
    ];
    private static $whiteListFilter = ['*'];

    /*************
     * Relaciones
     *************/
    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'cuestionario_id')->with('pregunta');
    }
}
