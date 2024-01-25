<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;

class DetalleResultadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;
    protected $table = 'med_detalles_resultados_examenes';
    protected $fillable = [
        'observacion',
        'tipo_examen_id',
        'examen_id',
    ];
    public function tipoExamen(){
        return $this->hasOne(TipoExamen::class,'id','tipo_examen_id');
    }
    public function examen(){
        return $this->hasOne(Examen::class,'id','examen_id');
    }
}
