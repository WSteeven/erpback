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
       //  'tiene_negocio_propio', //boolean
        'negocio_propio',
        'promedio_ingreso_mensual',
    ];
//[ERROR][317]: .Illuminate\Database\Eloquent\Relations\HasOneOrMany::create(): Argument #1 ($attributes) must be of type array, int given, called in C:\laragon\www\backend\src\App\RecursosHumanos\TrabajoSocial\FichaSocioeconomicaService.php on line 43
    public function ficha(){
        return $this->belongsTo(FichaSocioeconomica::class, 'ficha_id', 'id');
    }
}
