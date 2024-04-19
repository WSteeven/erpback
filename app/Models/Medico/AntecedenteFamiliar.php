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
        'descripcion',
        'tipo_antecedente_familiar_id',
        'parentesco',
        'antecedentable_id',
        'antecedentable_type',
    ];
    public function tipoAntecedenteFamiliar()
    {
        return $this->hasOne(TipoAntecedenteFamiliar::class);
    }
    public function antecedentable(){
        return $this->morphTo();
    }
}

