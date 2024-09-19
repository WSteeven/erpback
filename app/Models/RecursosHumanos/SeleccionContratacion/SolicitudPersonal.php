<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Archivo;
use App\Models\Autorizacion;
use App\Models\Cargo;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\SolicitudPersonal
 *
 * @method static create($validated)
 * @method static ignoreRequest(string[] $array)
 * @method static where(string $string, mixed $solicitud_id)
 * @method static find($solicitud_id)
 * @property mixed $autorizacion_id
 * @property mixed $solicitante
 * @property mixed $solicitante_id
 * @property mixed $autorizador_id
 * @property mixed $autorizador
 * @property mixed $id
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Autorizacion|null $autorizacion
 * @property-read Cargo|null $cargo
 * @property-read Modalidad|null $modalidad
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read TipoPuesto|null $tipoPuesto
 * @method static Builder|SolicitudPersonal acceptRequest(?array $request = null)
 * @method static Builder|SolicitudPersonal filter(?array $request = null)
 * @method static Builder|SolicitudPersonal newModelQuery()
 * @method static Builder|SolicitudPersonal newQuery()
 * @method static Builder|SolicitudPersonal query()
 * @method static Builder|SolicitudPersonal setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|SolicitudPersonal setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|SolicitudPersonal setLoadInjectedDetection($load_default_detection)
 * @property-read Collection<int, FormacionAcademica> $formacionesAcademicas
 * @property-read int|null $formaciones_academicas_count
 * @property string $nombre
 * @property int $publicada
 * @property int $tipo_puesto_id
 * @property int $modalidad_id
 * @property int|null $cargo_id
 * @property int|null $canton_id
 * @property int $num_plazas
 * @property string|null $areas_conocimiento
 * @property string $descripcion
 * @property string|null $anios_experiencia
 * @property bool $disponibilidad_viajar
 * @property bool $requiere_licencia
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|SolicitudPersonal whereAniosExperiencia($value)
 * @method static Builder|SolicitudPersonal whereAreasConocimiento($value)
 * @method static Builder|SolicitudPersonal whereAutorizacionId($value)
 * @method static Builder|SolicitudPersonal whereAutorizadorId($value)
 * @method static Builder|SolicitudPersonal whereCantonId($value)
 * @method static Builder|SolicitudPersonal whereCargoId($value)
 * @method static Builder|SolicitudPersonal whereCreatedAt($value)
 * @method static Builder|SolicitudPersonal whereDescripcion($value)
 * @method static Builder|SolicitudPersonal whereDisponibilidadViajar($value)
 * @method static Builder|SolicitudPersonal whereId($value)
 * @method static Builder|SolicitudPersonal whereModalidadId($value)
 * @method static Builder|SolicitudPersonal whereNombre($value)
 * @method static Builder|SolicitudPersonal whereNumPlazas($value)
 * @method static Builder|SolicitudPersonal wherePublicada($value)
 * @method static Builder|SolicitudPersonal whereRequiereLicencia($value)
 * @method static Builder|SolicitudPersonal whereSolicitanteId($value)
 * @method static Builder|SolicitudPersonal whereTipoPuestoId($value)
 * @method static Builder|SolicitudPersonal whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SolicitudPersonal extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_contratacion_solicitudes_nuevas_vacantes';

    protected $fillable = [
        'nombre',
        'publicada',
        'tipo_puesto_id',
        'modalidad_id',
        'solicitante_id',
        'autorizador_id',
        'autorizacion_id',
        'cargo_id',
        'anios_experiencia',
        'areas_conocimiento',
        'descripcion',
        'disponibilidad_viajar',
        'requiere_licencia',
        'canton_id',
        'num_plazas',

    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
        'disponibilidad_viajar' => 'boolean',
        'requiere_licencia' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function tipoPuesto()
    {
        return $this->hasOne(TipoPuesto::class, 'id', 'tipo_puesto_id');
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class);
    }

    public function cargo()
    {
        return $this->hasOne(Cargo::class, 'id', 'cargo_id');
    }

    public function solicitante()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function autorizador()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function autorizacion()
    {
        return $this->hasOne(Autorizacion::class, 'id', 'autorizacion_id');
    }

    public function formacionesAcademicas()
    {
        return $this->morphMany(FormacionAcademica::class, 'formacionable');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

}
