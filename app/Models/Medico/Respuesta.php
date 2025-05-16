<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\Medico\Respuesta
 *
 * @property int $id
 * @property string $respuesta
 * @property string $valor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\Cuestionario> $cuestionario
 * @property-read int|null $cuestionario_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\RespuestaCuestionarioEmpleado> $respuestaCuestionarioEmpleado
 * @property-read int|null $respuesta_cuestionario_empleado_count
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta query()
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta whereRespuesta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respuesta whereValor($value)
 * @mixin \Eloquent
 */
class Respuesta extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_respuestas';
    protected $fillable = [
        'respuesta',
        'valor',
    ];
    private static $whiteListFilter = ['*'];
    public function cuestionario(){
        return $this->hasMany(Cuestionario::class,'respuesta_id','id')->with('pregunta');
     }
     public function respuestaCuestionarioEmpleado(){
        return $this->hasMany(RespuestaCuestionarioEmpleado::class,'respuesta_id','id');
     }

}
