<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class CitaMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    const PENDIENTE = 'PENDIENTE';
    const AGENDADO = 'AGENDADO';
    const ATENDIDO = 'ATENDIDO';
    const CANCELADO = 'CANCELADO';
    const RECHAZADO = 'RECHAZADO';

    protected $table = 'med_citas_medicas';
    protected $fillable = [
        'sintomas',
        'observacion',
        'fecha_hora_cita',
        'estado_cita_medica',
        'paciente_id',
        'motivo_rechazo',
        'motivo_cancelacion',
        'fecha_hora_rechazo',
        'fecha_hora_cancelacion',
    ];

    private static $whiteListFilter = ['*'];

    /*************
     * Relaciones
     *************/
    /*public function estadoCitaMedica()
    {
        return $this->belongsToMany(EstadoCitaMedica::class, 'estado_cita_medica_id');
    }*/

    public function paciente()
    {
        return $this->belongsTo(Empleado::class, 'paciente_id', 'id');
    }
}
