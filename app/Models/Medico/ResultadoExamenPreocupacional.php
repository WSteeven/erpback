<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ResultadoExamenPreocupacional extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_resultados_examenes_preocupacionales';
    protected $fillable = [
        'nombre',
        'tiempo',
        'resultados',
        'genero',
        'antecedente_personal_id',
        'ficha_preocupacional_id'
    ];
    public function antecedentePersonal(){
        return $this->hasOne(AntecedentePersonal::class,'id','antecedente_personal_id');
    }

}
