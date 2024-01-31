<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class ActividadPuestoTrabajo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_actividades_puestos_trabajos';
    protected $fillable = [
        'actividad',
        'preocupacional_id',
    ];

    public function preocupacional(){
        return $this->hasOne(Preocupacional::class, 'id','preocupacional_id');
    }
}
