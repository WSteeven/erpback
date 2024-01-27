<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Preocupacional extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_preocupacionales';
    protected $fillable = [
        'ciu',
        'esatblecimiento_salud',
        'numero_historia_clinica',
        'numero_archivo',
        'puesto_trabajo',
        'religion_id',
        'orientacion_sexual_id',
        'identidad_genero_id',
        'actividades_relevantes_puesto_trabajo_ocupar',
        'motivo_consulta',
        'empleado_id',
        'actividad_fisica',
        'enfermedad_actual',
        'recomendaciones_tratamiento',
    ];
    public function orientacionSexual(){
        return $this->hasOne(OrientacionSexual::class,'id','orientacion_sexual_id');
    }
    public function identidadGenero(){
        return $this->hasOne(IdentidadGenero::class,'id','identidad_genero_id');
    }
    public function empleado (){
        return $this->hasOne(Empleado::class,'id','empleado_id');
    }
}
