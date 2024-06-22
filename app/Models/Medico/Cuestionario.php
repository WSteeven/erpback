<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

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
