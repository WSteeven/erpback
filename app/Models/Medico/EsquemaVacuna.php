<?php

namespace App\Models\Medico;

use App\Models\Archivo;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class EsquemaVacuna extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_esquemas_vacunas';
    protected $fillable = [
        'dosis_aplicadas',
        'observacion',
        'fecha',
        'lote',
        'responsable_vacunacion',
        'establecimiento_salud',
        'es_dosis_unica',
        'fecha_caducidad',
        'paciente_id',
        'tipo_vacuna_id',
    ];

    private static $whiteListFilter = ['*'];

    // Relaciones
    /* public function registroEmpleadoExamen()
    {
        return $this->hasOne(RegistroEmpleadoExamen::class, 'id', 'registro_examen_id');
    } */

    public function tipoVacuna()
    {
        return $this->hasOne(TipoVacuna::class, 'id', 'tipo_vacuna_id');
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
