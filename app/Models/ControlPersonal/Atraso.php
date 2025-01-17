<?php

namespace App\Models\ControlPersonal;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Atraso extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_cp_atrasos';
    protected $fillable = [
        'empleado_id',
        //'revisador_id', //se supone el jefe inmediato o rrhh en su respectivo caso, aun no se sabe si definir variable
        'marcacion_id',
        'fecha_atraso',
        'ocurrencia', // para saber si ocurrio en la hora_entrada o fin_pausa segun el horario laboral
        'segundos_atraso',
        'justificado',
        'justificacion',
        'imagen_evidencia',
        'revisado', //pendiente, revisado
    ];

    protected $casts = [
//  'minutos_atraso'=>'i:s'
    ];

    const ENTRADA = 'ENTRADA';
    const PAUSA = 'PAUSA';


    protected function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

}
