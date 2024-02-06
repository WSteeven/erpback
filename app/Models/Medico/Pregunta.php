<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
class Pregunta extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_preguntas';
    protected $fillable = [
        'codigo',
        'pregunta',
    ];
    private static $whiteListFilter = ['*'];
     public function cuestionario(){
        return $this->hasMany(Cuestionario::class,'pregunta_id','id')->with('respuesta');
     }
     public function respuestaCuestionarioEmpleado(){
        return $this->hasMany(RespuestaCuestionarioEmpleado::class,'pregunta_id','id');
     }

}
