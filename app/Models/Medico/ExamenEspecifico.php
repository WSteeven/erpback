<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ExamenEspecifico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_examenes_especificos';
    protected $fillable = [
        'examen',
        'fecha',
        'resultados',
        'preocupacional_id',
    ];
    public function preocupacional(){
        return $this->hasOne(Preocupacional::class, 'id','preocupacional_id');
    }

}
