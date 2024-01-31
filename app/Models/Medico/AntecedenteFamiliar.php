<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class AntecedenteFamiliar extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_antecedentes_familiares';
    protected $fillable = [
        'tipo_antecedente_familiar_id',
        'preocupacional_id',
    ];
    public function tipoAntecedenteFamiliar()
    {
        return $this->hasOne(TipoAntecedenteFamiliar::class, 'id', 'tipo_antecedente_familiares_id');
    }
    public function preocupacional(){
        return $this->hasOne(Preocupacional::class, 'id','preocupacional_id');
    }
}

