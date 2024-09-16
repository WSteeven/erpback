<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Canton;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Src\Shared\ObtenerInstanciaUsuario;


/**
 * App\Models\RecursosHumanos\SeleccionContratacion\Vacante
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @property-read \App\Models\RecursosHumanos\SeleccionContratacion\Favorita|null $favorita
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecursosHumanos\SeleccionContratacion\FormacionAcademica> $formacionesAcademicas
 * @property-read int|null $formaciones_academicas_count
 * @property-read \App\Models\RecursosHumanos\SeleccionContratacion\Modalidad|null $modalidad
 * @property-read \App\Models\RecursosHumanos\SeleccionContratacion\Postulacion|null $postulacion
 * @property-read Empleado|null $publicante
 * @property-read \App\Models\RecursosHumanos\SeleccionContratacion\SolicitudPersonal|null $solicitud
 * @property-read \App\Models\RecursosHumanos\SeleccionContratacion\TipoPuesto|null $tipoPuesto
 * @method static \Illuminate\Database\Eloquent\Builder|Vacante acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacante filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacante ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacante newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacante newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacante query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacante setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacante setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacante setLoadInjectedDetection($load_default_detection)
 * @mixin \Eloquent
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
