<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Archivo;
use App\Models\Notificacion;
use App\Models\Pais;
use App\Models\User;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;


/**
 * App\Models\RecursosHumanos\SeleccionContratacion\Postulacion
 *
 * @method static find(mixed $postulacion)
 * @method static ignoreRequest(string[] $array)
 * @method static where(string $string, mixed $id)
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Entrevista|null $entrevista
 * @property-read Examen|null $examen
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Pais|null $paisResidencia
 * @property-read Model|Eloquent $postulacionable
 * @property-read Vacante|null $vacante
 * @method static Builder|Postulacion acceptRequest(?array $request = null)
 * @method static Builder|Postulacion filter(?array $request = null)
 * @method static Builder|Postulacion newModelQuery()
 * @method static Builder|Postulacion newQuery()
 * @method static Builder|Postulacion query()
 * @method static Builder|Postulacion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Postulacion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Postulacion setLoadInjectedDetection($load_default_detection)
 * @property int $id
 * @property int $user_id
 * @property string $user_type
 * @property int $vacante_id
 * @property int $pais_residencia_id
 * @property string $direccion
 * @property string|null $mi_experiencia
 * @property bool $tengo_conocimientos_requeridos
 * @property bool $tengo_disponibilidad_viajar
 * @property bool $tengo_documentos_regla
 * @property bool $tengo_experiencia_requerida
 * @property bool $tengo_formacion_academica_requerida
 * @property bool $tengo_licencia_conducir
 * @property string|null $tipo_licencia
 * @property string $calificacion
 * @property string $estado
 * @property string|null $ruta_cv
 * @property bool $leido_rrhh
 * @property bool $activo
 * @property bool $dado_alta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Postulacion whereActivo($value)
 * @method static Builder|Postulacion whereCalificacion($value)
 * @method static Builder|Postulacion whereCreatedAt($value)
 * @method static Builder|Postulacion whereDadoAlta($value)
 * @method static Builder|Postulacion whereDireccion($value)
 * @method static Builder|Postulacion whereEstado($value)
 * @method static Builder|Postulacion whereId($value)
 * @method static Builder|Postulacion whereLeidoRrhh($value)
 * @method static Builder|Postulacion whereMiExperiencia($value)
 * @method static Builder|Postulacion wherePaisResidenciaId($value)
 * @method static Builder|Postulacion whereRutaCv($value)
 * @method static Builder|Postulacion whereTengoConocimientosRequeridos($value)
 * @method static Builder|Postulacion whereTengoDisponibilidadViajar($value)
 * @method static Builder|Postulacion whereTengoDocumentosRegla($value)
 * @method static Builder|Postulacion whereTengoExperienciaRequerida($value)
 * @method static Builder|Postulacion whereTengoFormacionAcademicaRequerida($value)
 * @method static Builder|Postulacion whereTengoLicenciaConducir($value)
 * @method static Builder|Postulacion whereTipoLicencia($value)
 * @method static Builder|Postulacion whereUpdatedAt($value)
 * @method static Builder|Postulacion whereUserId($value)
 * @method static Builder|Postulacion whereUserType($value)
 * @method static Builder|Postulacion whereVacanteId($value)
 * @mixin Eloquent
 */
class Postulacion extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = 'rrhh_contratacion_postulaciones';

    protected $fillable = [
        'mi_experiencia',
        'vacante_id',
        'direccion',
        'pais_residencia_id',
        'tengo_conocimientos_requeridos',
        'tengo_disponibilidad_viajar',
        'tengo_documentos_regla',
        'tengo_experiencia_requerida',
        'tengo_formacion_academica_requerida',
        'tengo_licencia_conducir',
        'aspiracion_salarial',
        'tipo_licencia',
        'calificacion',
        'activo',
        'user_id',
        'user_type',
        'ruta_cv',
        'leido_rrhh',
        'estado',
        'token_test',
        'dado_alta',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'tengo_conocimientos_requeridos' => 'boolean',
        'tengo_disponibilidad_viajar' => 'boolean',
        'tengo_documentos_regla' => 'boolean',
        'tengo_experiencia_requerida' => 'boolean',
        'tengo_formacion_academica_requerida' => 'boolean',
        'tengo_licencia_conducir' => 'boolean',
        'leido_rrhh' => 'boolean',
        'activo' => 'boolean',
        'dado_alta' => 'boolean',
    ];
    // --------------------------------
    // ESTADOS
    // --------------------------------
    const POSTULADO = 'POSTULADO';
    const REVISION_CV = 'REVISION CV';
    const ENTREVISTA = 'EN ENTREVISTA';
    const TEST_PERSONALIDAD = 'EN TEST PERSONALIDAD';
    const TEST_VALANTI = 'EN TEST VALANTI';
    const DESCARTADO = 'DESCARTADO';
    const PRESELECCIONADO = 'PRESELECCIONADO';
    const SELECCIONADO = 'SELECCIONADO';
    const EXAMENES_MEDICOS = 'EXAMENES MEDICOS';
    const CONTRATADO = 'CONTRATADO';
    const BANCO_DE_CANDIDATOS = 'BANCO DE CANDIDATOS';
    const RECHAZADO = 'RECHAZADO';

    // -------------------------------------
    // CALIFICACIONES
    // -------------------------------------
    const NO_CALIFICADO = 'NO CALIFICADO';
//    const ALTA_PRIORIDAD = 'ALTA PRIORIDAD';
//    const BAJA_PRIORIDAD = 'BAJA PRIORIDAD';
    const NO_CONSIDERAR = 'NO CONSIDERAR';

    private static array $whiteListFilter = ['*'];

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    public function vacante()
    {
        return $this->belongsTo(Vacante::class, 'vacante_id');
    }

    public function evaluacionPersonalidad()
    {
        return $this->hasOne(EvaluacionPersonalidad::class );
    }

    public function paisResidencia()
    {
        return $this->belongsTo(Pais::class, 'pais_residencia_id', 'id');
    }

    /**
     * Relación uno a uno.
     * Una postulacion puede tener 0 o 1 entrevista
     * @return HasOne
     */
    public function entrevista()
    {
        return $this->hasOne(Entrevista::class, 'postulacion_id', 'id');
    }

    /**
     * Relación uno a uno.
     * Una postulacion puede tener 0 o 1 examenes
     * @return HasOne
     */
    public function examen()
    {
        return $this->hasOne(Examen::class, 'postulacion_id', 'id');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }


    public function postulacionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        // Determina el tipo de usuario autenticado
        if ($this->user_type === User::class) {
            return $this->belongsTo(User::class, 'user_id', 'id');
        }
        if ($this->user_type === UserExternal::class) {
            return $this->belongsTo(UserExternal::class, 'user_id', 'id');
        }
        return [];
    }
}
