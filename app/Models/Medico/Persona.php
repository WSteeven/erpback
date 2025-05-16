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

/**
 * App\Models\Medico\Persona
 *
 * @property int $id
 * @property string $primer_nombre
 * @property string $segundo_nombre
 * @property string $primer_apellido
 * @property string $segundo_apellido
 * @property string|null $area
 * @property string|null $nivel_academico
 * @property string|null $antiguedad
 * @property string|null $correo
 * @property string|null $genero
 * @property string|null $nombre_empresa
 * @property string|null $ruc
 * @property string|null $cargo
 * @property string|null $identificacion
 * @property string|null $fecha_nacimiento
 * @property string|null $tipo_afiliacion_seguridad_social
 * @property string|null $nivel_instruccion
 * @property int $numero_hijos
 * @property string|null $autoidentificacion_etnica
 * @property string|null $porcentaje_discapacidad
 * @property int $es_trabajador_sustituto
 * @property string|null $enfermedades_preexistentes
 * @property int $ha_recibido_capacitacion
 * @property int $tiene_examen_preocupacional
 * @property int $estado_civil_id
 * @property int $provincia_id
 * @property int $canton_id
 * @property int $tipo_cuestionario_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @property-read \App\Models\Medico\CuestionarioPublico|null $cuestionarioPublico
 * @property-read EstadoCivil|null $estadoCivil
 * @property-read Provincia|null $provincia
 * @property-read \App\Models\Medico\TipoCuestionario|null $tipoCuestionario
 * @method static \Illuminate\Database\Eloquent\Builder|Persona acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Persona newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Persona query()
 * @method static \Illuminate\Database\Eloquent\Builder|Persona setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona tipoCuestionario($tipoCuestionarioId)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereAntiguedad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereAutoidentificacionEtnica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereCargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereCorreo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereEnfermedadesPreexistentes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereEsTrabajadorSustituto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereEstadoCivilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereFechaNacimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereGenero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereHaRecibidoCapacitacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereIdentificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereNivelAcademico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereNivelInstruccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereNombreEmpresa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereNumeroHijos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona wherePorcentajeDiscapacidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona wherePrimerApellido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona wherePrimerNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereProvinciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereRuc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereSegundoApellido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereSegundoNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereTieneExamenPreocupacional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereTipoAfiliacionSeguridadSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereTipoCuestionarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Persona whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
