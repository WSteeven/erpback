<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Canton;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Src\Shared\ObtenerInstanciaUsuario;


/**
 * App\Models\RecursosHumanos\SeleccionContratacion\Vacante
 *
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @property-read Favorita|null $favorita
 * @property-read Collection<int, FormacionAcademica> $formacionesAcademicas
 * @property-read int|null $formaciones_academicas_count
 * @property-read Modalidad|null $modalidad
 * @property-read Postulacion|null $postulacion
 * @property-read Empleado|null $publicante
 * @property-read SolicitudPersonal|null $solicitud
 * @property-read TipoPuesto|null $tipoPuesto
 * @method static Builder|Vacante acceptRequest(?array $request = null)
 * @method static Builder|Vacante filter(?array $request = null)
 * @method static Builder|Vacante ignoreRequest(?array $request = null)
 * @method static Builder|Vacante newModelQuery()
 * @method static Builder|Vacante newQuery()
 * @method static Builder|Vacante query()
 * @method static Builder|Vacante setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Vacante setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Vacante setLoadInjectedDetection($load_default_detection)
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property string $fecha_caducidad
 * @property string $imagen_referencia
 * @property string $imagen_publicidad
 * @property string|null $anios_experiencia
 * @property string|null $areas_conocimiento
 * @property int $numero_postulantes
 * @property int $tipo_puesto_id
 * @property int $modalidad_id
 * @property int $publicante_id
 * @property int|null $solicitud_id
 * @property int|null $canton_id
 * @property int $num_plazas
 * @property bool $disponibilidad_viajar
 * @property bool $requiere_licencia
 * @property bool $activo
 * @property bool $es_completada
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Vacante whereActivo($value)
 * @method static Builder|Vacante whereAniosExperiencia($value)
 * @method static Builder|Vacante whereAreasConocimiento($value)
 * @method static Builder|Vacante whereCantonId($value)
 * @method static Builder|Vacante whereCreatedAt($value)
 * @method static Builder|Vacante whereDescripcion($value)
 * @method static Builder|Vacante whereDisponibilidadViajar($value)
 * @method static Builder|Vacante whereEsCompletada($value)
 * @method static Builder|Vacante whereFechaCaducidad($value)
 * @method static Builder|Vacante whereId($value)
 * @method static Builder|Vacante whereImagenPublicidad($value)
 * @method static Builder|Vacante whereImagenReferencia($value)
 * @method static Builder|Vacante whereModalidadId($value)
 * @method static Builder|Vacante whereNombre($value)
 * @method static Builder|Vacante whereNumPlazas($value)
 * @method static Builder|Vacante whereNumeroPostulantes($value)
 * @method static Builder|Vacante wherePublicanteId($value)
 * @method static Builder|Vacante whereRequiereLicencia($value)
 * @method static Builder|Vacante whereSolicitudId($value)
 * @method static Builder|Vacante whereTipoPuestoId($value)
 * @method static Builder|Vacante whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Vacante extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_contratacion_vacantes';
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_caducidad',
        'imagen_referencia',
        'imagen_publicidad',
        'anios_experiencia',
        'areas_conocimiento',
        'numero_postulantes',
        'tipo_puesto_id',
        'modalidad_id',
        'publicante_id',
        'solicitud_id',
        'activo',
        'disponibilidad_viajar',
        'requiere_licencia',
        'es_completada',
        'canton_id',
        'num_plazas',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
        'disponibilidad_viajar' => 'boolean',
        'requiere_licencia' => 'boolean',
        'es_completada' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function tipoPuesto()
    {
        return $this->hasOne(TipoPuesto::class, 'id', 'tipo_puesto_id');
    }

    public function publicante()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudPersonal::class);
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class);
    }

    public function formacionesAcademicas()
    {
        return $this->morphMany(FormacionAcademica::class, 'formacionable');
    }

    public function  canton()
    {
        return $this->belongsTo(Canton::class);
    }
    public function favorita()
    {
        [$user_id, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();

        // Si hay un usuario autenticado, retorna una instancia de la relación
        return $this->hasOne(Favorita::class, 'vacante_id')
            ->where('user_id', $user_id)
            ->where('user_type', $user_type);
    }

    public function postulacion()
    {

        [$user_id, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();

        return $this->hasOne(Postulacion::class, 'vacante_id')
            ->where('user_id', $user_id)
            ->where('user_type', $user_type);
    }
}
