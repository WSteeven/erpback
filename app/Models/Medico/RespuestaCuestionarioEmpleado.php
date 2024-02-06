<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
class RespuestaCuestionarioEmpleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_respuestas_cuestionarios_empleados';
    protected $fillable = [
        'pregunta_id',
        'respuesta_id',
        'empleado_id'
    ];
    private static $whiteListFilter = ['*'];

    public function pregunta(){
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }
    public function respuesta(){
        return $this->belongsTo(Respuesta::class, 'respuesta_id');
    }
    public function empleado(){
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

}
