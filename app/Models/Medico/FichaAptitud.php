<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class FichaAptitud extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_fichas_aptitudes';
    protected $fillable = [
        'fecha_emision',
        'observaciones_aptitud_medica',
        'recomendaciones',
        'tipo_evaluacion_id',
        'tipo_aptitud_medica_laboral_id',
        'tipo_evaluacion_medica_retiro_id',
        'preocupacional_id'
    ];
    public function tipoEvaluacion(){
        return $this->belongsTo(TipoEvaluacion::class,'tipo_evaluacion_id');
    }
    public function tipoAptitudMedicaLaboral(){
        return $this->belongsTo(TipoAptitudMedicaLaboral::class,'tipo_aptitud_medica_laboral_id');
    }
    public function tipoEvaluacionMedicaRetiro(){
        return $this->belongsTo(TipoEvaluacionMedicaRetiro::class,'tipo_evaluacion_medica_retiro_id');
    }
    public function preocupacional(){
        return $this->belongsTo(Preocupacional::class,'preocupacional_id');
    }
    public function profesionalSalud(){
        return $this->hasOne(ProfesionalSalud::class,'ficha_aptitud_id','id');
    }
}
