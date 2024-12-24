<?php

namespace App\Models\RecursosHumanos\TrabajoSocial;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class SituacionSocioeconomica extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_situaciones_socioeconomicas';
    protected $fillable = [
        'ficha_id',  // el id de la ficha socioeconomica
        'empleado_id',  // el id del empleado para cuando se quiera acceder directamente
        'cantidad_personas_aportan',
        'cantidad_personas_dependientes',
        'recibe_apoyo_economico_otro_familiar',
        'familiar_apoya_economicamente',
        'recibe_apoyo_economico_gobierno',
        'institucion_apoya_economicamente',
        'tiene_prestamos',
        'cantidad_prestamos',
        'entidad_bancaria',
        'tiene_tarjeta_credito',
        'cantidad_tarjetas_credito',
        'vehiculo',
        'tiene_terreno',
        'tiene_bienes',
        'tiene_ingresos_adicionales',
        'ingresos_adicionales',
        'apoya_familiar_externo',
        'familiar_externo_apoyado',
    ];

    protected $casts = [
        'recibe_apoyo_economico_otro_familiar'=> 'boolean',
        'recibe_apoyo_economico_gobierno'=> 'boolean',
        'tiene_prestamos'=> 'boolean',
        'tiene_tarjeta_credito'=> 'boolean',
        'tiene_terreno'=> 'boolean',
        'tiene_bienes'=> 'boolean',
        'tiene_ingresos_adicionales'=> 'boolean',
        'apoya_familiar_externo'=> 'boolean',
    ];

    public function ficha(){
        return $this->belongsTo(FichaSocioeconomica::class, 'ficha_id', 'id');
    }

}
