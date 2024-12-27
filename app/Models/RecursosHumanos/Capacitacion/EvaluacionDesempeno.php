<?php

namespace App\Models\RecursosHumanos\Capacitacion;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class EvaluacionDesempeno extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'rrhh_cap_evaluaciones_desempenos';
    protected $fillable = [
        'evaluado_id',
        'evaluador_id',
        'calificacion',
        'formulario_id',
        'respuestas',
    ];

    protected $casts = [
        'respuestas' => 'array',
    ];

    public function evaluado(){
        return $this->belongsTo(Empleado::class, 'evaluado_id', 'id');
    }

    public function evaluador(){
        return $this->belongsTo(Empleado::class, 'evaluador_id', 'id');
    }

    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }
}
