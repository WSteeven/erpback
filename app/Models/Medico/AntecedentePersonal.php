<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class AntecedentePersonal extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_antecedentes_personales';
    protected $fillable = [
        'antecedentes_quirorgicos',
        'vida_sexual_activa',
        'tiene_metodo_planificacion_familiar',
        'tipo_metodo_planificacion_familiar',
        'ficha_preocupacional_id',
    ];
    public function fichaPreocupacional()
    {
        return $this->hasOne(FichaPreocupacional::class, 'id', 'ficha_preocupacional_id');
    }
    public function antecedenteGinecoobstetrico()
    {
        return $this->hasOne(AntecedenteGinecoObstetrico::class, 'antecedentes_personales_id', 'id');
    }
    public function examenfichaPreocupacional(){
        return $this->hasOne(ResultadoExamenPreocupacional::class,'antecedentes_personales_id', 'id');
    }
}
