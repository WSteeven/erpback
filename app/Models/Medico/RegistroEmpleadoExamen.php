<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class RegistroEmpleadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    // Contantes
    const INGRESO = 'INGRESO';
    const PERIODICO = 'PERIODICO';
    const REINTEGRO = 'REINTEGRO';
    const RETIRO = 'RETIRO';

    protected $table = 'med_registros_empleados_examenes';
    protected $fillable = [
        'numero_registro',
        'observacion',
        'tipo_proceso_examen',
        'empleado_id',
    ];

    private static $whiteListFilter = ['*'];

    // Relaciones
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function estadosSolicitudesExamenes()
    {
        return $this->hasMany(EstadoSolicitudExamen::class, 'id', 'registro_empleado_examen_id')->with('examen', 'estadoExamen');
    }

    public function fichaAptitud()
    {
        return $this->hasOne(FichaAptitud::class);
    }

    public function fichaPreocupacional()
    {
        return $this->hasOne(FichaPreocupacional::class);
    }

    public function fichaPeriodica()
    {
        return $this->hasOne(FichaPeriodica::class);
    }

    public function fichaReintegro()
    {
        return $this->hasOne(FichaReintegro::class);
    }

    public function fichaRetiro()
    {
        return $this->hasOne(FichaRetiro::class);
    }
}
