<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ProfesionalSalud extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_profesionales_salud';
    protected $fillable = [
        'nombres',
        'apellidos',
        'codigo',
        'ficha_aptitud_id'
    ];
    public function fichaAptitud(){
        return $this->belongsTo(FichaAptitud::class,'ficha_aptitud_id');
    }

}
