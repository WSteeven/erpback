<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class FrPuestoTrabajoActual extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_fr_puestos_trabajos_actuales';
    protected $fillable = [
        'puesto_trabajo',
        'actividad',
        'medidas_preventivas',
        'ficha_preocupacional_id',
    ];
    public function DetalleCategFactorRiesgoFrPuestoTrabAct(){
        return $this->belongsToMany(DetalleCategFactorRiesgoFrPuestoTrabAct::class);
    }
    public function fichaPreocupacional(){
        return $this->hasOne(FichaPreocupacional::class, 'id','ficha_preocupacional_id');
    }
}
