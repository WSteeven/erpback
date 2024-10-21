<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\Cuestionario
 *
 * @property int $id
 * @property string|null $respuesta_texto
 * @property int $pregunta_id
 * @property int|null $respuesta_id
 * @property int $tipo_cuestionario_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\Pregunta|null $pregunta
 * @property-read \App\Models\Medico\Respuesta|null $respuesta
 * @property-read \App\Models\Medico\RespuestaCuestionarioEmpleado|null $respuestasCuestionariosEmpleados
 * @property-read \App\Models\Medico\TipoCuestionario|null $tipoCuestionario
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario wherePreguntaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario whereRespuestaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario whereRespuestaTexto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario whereTipoCuestionarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cuestionario whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cuestionario extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;
    protected $table = 'med_cuestionarios';
    protected $fillable = [
        'pregunta_id',
        'respuesta_id',
        'tipo_cuestionario_id',
        'respuesta_texto',
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }

    public function respuesta()
    {
        return $this->belongsTo(Respuesta::class, 'respuesta_id');
    }

    public function respuestasCuestionariosEmpleados()
    {
        return $this->belongsTo(RespuestaCuestionarioEmpleado::class, 'pregunta_id')->with('cuestionario');
    }

    public function tipoCuestionario()
    {
        return $this->belongsTo(TipoCuestionario::class);
    }
}
