<?php

namespace App\Models\Medico;

use App\ModelFilters\Medico\ExamenFilter;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class CitaMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel, ExamenFilter;

    protected $table = 'med_citas_medicas';
    protected $fillable = [
        'sintomas',
        'razon',
        'observacion',
        'fecha_hora_cita',
        'estado_cita_medica_id',
        'paciente_id'

    ];
    private static $whiteListFilter = ['*'];

    /*************
     * Relaciones
     *************/
    public function estadoCitaMedica()
    {
        return $this->belongsToMany(EstadoCitaMedica::class, 'estado_cita_medica_id');
    }

    public function paciente()
    {
        return $this->belongsToMany(Empleado::class, 'paciente_id');
    }


}
