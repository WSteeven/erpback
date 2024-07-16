<?php

namespace App\Models\Medico;

use App\ModelFilters\Medico\FichaAptitudFilter;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class FichaAptitud extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, FichaAptitudFilter;

    protected $table = 'med_fichas_aptitudes';
    protected $fillable = [
        'recomendaciones',
        'observaciones_aptitud_medica',
        'firmado_profesional_salud',
        'firmado_paciente',
        'registro_empleado_examen_id',
        'tipo_aptitud_medica_laboral_id',
        'profesional_salud_id',
    ];

    private static $whiteListFilter = ['*'];

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }

    public function tipoAptitudMedicaLaboral()
    {
        return $this->belongsTo(TipoAptitudMedicaLaboral::class, 'tipo_aptitud_medica_laboral_id');
    }

    public function profesionalSalud()
    {
        return $this->belongsTo(ProfesionalSalud::class, 'id', '');//, 'id', 'ficha_aptitud_id');
        // return $this->hasOne(ProfesionalSalud::class, 'id', 'ficha_aptitud_id');
    }

    public function opcionesRespuestasTipoEvaluacionMedicaRetiro()
    {
        return $this->hasMany(OpcionRespuestaTipoEvaluacionMedicaRetiro::class);
    }
}
