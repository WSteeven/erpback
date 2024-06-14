<?php

namespace App\Models\Medico;

use App\Models\Canton;
use App\Models\EstadoCivil;
use App\Models\Provincia;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Persona extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_personas';
    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'area',
        'nivel_academico',
        'antiguedad',
        'correo',
        'genero',
        'nombre_empresa',
        'ruc',
        'cargo',
        'identificacion',
        'fecha_nacimiento',
        'tipo_afiliacion_seguridad_social',
        'nivel_instruccion',
        'numero_hijos',
        'autoidentificacion_etnica',
        'porcentaje_discapacidad',
        'es_trabajador_sustituto',
        'enfermedades_preexistentes',
        'ha_recibido_capacitacion',
        'tiene_examen_preocupacional',
        'estado_civil_id',
        'provincia_id',
        'canton_id',
        'tipo_cuestionario_id',
    ];

    private static $whiteListFilter = ['*'];

    /**************
     * Relaciones
     **************/
    public function estadoCivil()
    {
        return $this->belongsTo(EstadoCivil::class);
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function cuestionarioPublico()
    {
        return $this->hasOne(CuestionarioPublico::class);
    }

    public function tipoCuestionario()
    {
        return $this->belongsTo(TipoCuestionario::class);
    }

    /*********
     * Scopes
     *********/
    public function scopeTipoCuestionario($query, $tipoCuestionarioId)
    {
        return $query->where('tipo_cuestionario_id', $tipoCuestionarioId);
    }

    /*************
     * Funciones
     *************/
    public static function extraerNombresApellidos(Persona $persona)
    {
        return $persona->primer_nombre . ' ' .  $persona->segundo_nombre . ' ' . $persona->primer_apellido . ' ' . $persona->segundo_apellido;
    }
}
