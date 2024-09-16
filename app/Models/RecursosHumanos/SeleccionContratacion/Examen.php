<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Canton;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;


/**
 * App\Models\RecursosHumanos\SeleccionContratacion\Examen
 *
 * @method static create($datos)
 * @method static find(mixed $id)
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Postulacion|null $postulacion
 * @method static Builder|Examen acceptRequest(?array $request = null)
 * @method static Builder|Examen filter(?array $request = null)
 * @method static Builder|Examen ignoreRequest(?array $request = null)
 * @method static Builder|Examen newModelQuery()
 * @method static Builder|Examen newQuery()
 * @method static Builder|Examen query()
 * @method static Builder|Examen setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Examen setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Examen setLoadInjectedDetection($load_default_detection)
 * @mixin Eloquent
 */
class Examen extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'rrhh_contratacion_examenes';
    protected $fillable = [
        'postulacion_id',
        'fecha_hora',
        'canton_id',
        'direccion',
        'laboratorio',
        'indicaciones',
        'se_realizo_examen',
        'es_apto',
        'observacion'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'se_realizo_examen' => 'boolean',
        'es_apto' => 'boolean',
    ];

    public function getKeyName()
    {
        return 'postulacion_id';
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable',null, null, 'postulacion_id');
    }
    /**
     * Relación uno a uno.
     * Un solo examen médico se emite para una postulación
     */
    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class, 'postulacion_id');
    }
    public function canton()
    {
        return $this->belongsTo(Canton::class, 'canton_id');
    }

}
