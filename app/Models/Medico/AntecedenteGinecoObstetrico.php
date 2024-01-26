<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class AntecedenteGinecoObstetrico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_antecedentes_gineco_obstetricos';
    protected $fillable = [
        'menarquia',
        'ciclos',
        'fecha_ultima_menstruacion',
        'gestas',
        'partos',
        'cesareas',
        'abortos',
        'antecedentes_personales_id',
    ];
    public function antecedentesPersonales()
    {
        return $this->hasOne(AntecedentePersonal::class, 'id', 'antecedentes_personales_id');
    }
}
