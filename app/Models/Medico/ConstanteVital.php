<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ConstanteVital extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_constantes_vitales';
    protected $fillable = [
        'presion_arterial',
        'temperatura',
        'frecuencia_cardiaca',
        'saturacion_oxigeno',
        'frecuencia_respiratoria',
        'peso',
        'talla',
        'indice_masa_corporal',
        'perimetro_abdominal',
        'constante_vitalable_id',
        'constante_vitalable_type',
    ];


    public function constanteVitalable(){
        return $this->morphTo();
    }

}
