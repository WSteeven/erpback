<?php

namespace App\Models\RecursosHumanos\TrabajoSocial;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class Conyuge extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_conyuges';
    protected $fillable = [
        'ficha_id',  // el id de la ficha socioeconomica
        'empleado_id',  // el id del empleado para cuando se quiera acceder directamente
        'nombres',
        'apellidos',
        'nivel_academico',
        'edad',
        'profesion',
        'telefono',
        'tiene_dependencia_laboral', //boolean
        'promedio_ingreso_mensual',
    ];

    public function ficha(){
        return $this->belongsTo(FichaSocioeconomica::class, 'ficha_id', 'id');
    }
}
