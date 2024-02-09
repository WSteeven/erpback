<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Consulta extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_consultas';
    protected $primaryKey = 'cita_id';
    public $incrementing = false;

    // Obtener la llave primaria
    public function getKeyName()
    {
        return 'cita_id';
    }

    protected $fillable = [
        'empleado_id',
        'cita_id',
        'rp',
        'prescripcion',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    /*public function diagnosticosCita()
    {
        return $this->belongsToMany(DiagnosticoCita::class);//, 'diagnostico_cita_id', 'id');
    } */

    public function cita()
    {
        return $this->belongsTo(CitaMedica::class, 'cita_id');
    }
}
