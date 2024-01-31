<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ExamenPreocupacional extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_examenes_preocupacionales';
    protected $fillable = [
        'nombre',
        'tiempo',
        'resultados',
        'genero',
        'antecedente_personal_id',
    ];
    public function antecedentePersonal(){
        return $this->hasOne(AntecedentePersonal::class,'id','antecedente_personal_id');
    }

}
