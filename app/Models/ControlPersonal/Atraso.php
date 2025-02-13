<?php

namespace App\Models\ControlPersonal;

use App\Models\Empleado;
use App\Models\Notificacion;
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
        'justificador_id', //se supone el jefe inmediato o rrhh en su respectivo caso, aun no se sabe si definir variable
        'marcacion_id',
        'fecha_atraso',
        'ocurrencia', // para saber si ocurrio en la hora_entrada o fin_pausa segun el horario laboral
        'segundos_atraso',
        'justificado', //boolean
        'justificacion',
        'imagen_evidencia',
        'revisado', //pendiente, revisado //boolean
    ];

    protected $casts = [
        'justificado' => 'boolean',
        'revisado' => 'boolean',
    ];
    private static array $whiteListFilter = ['*'];
    const ENTRADA = 'HORA DE ENTRADA';
    const PAUSA = 'FIN PAUSA';


    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function justificador()
    {
        return $this->belongsTo(Empleado::class, 'justificador_id');
    }

    public function marcacion()
    {
        return $this->belongsTo(Marcacion::class);
    }

    /**
     * Relación polimorfica a una notificación.
     * Un pedido puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
}
