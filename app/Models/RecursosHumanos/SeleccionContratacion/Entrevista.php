<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

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
 * App\Models\RecursosHumanos\SeleccionContratacion\Entrevista
 *
 * @method static create(mixed $datos)
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Postulacion|null $postulacion
 * @method static Builder|Entrevista acceptRequest(?array $request = null)
 * @method static Builder|Entrevista filter(?array $request = null)
 * @method static Builder|Entrevista ignoreRequest(?array $request = null)
 * @method static Builder|Entrevista newModelQuery()
 * @method static Builder|Entrevista newQuery()
 * @method static Builder|Entrevista query()
 * @method static Builder|Entrevista setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Entrevista setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Entrevista setLoadInjectedDetection($load_default_detection)
 * @mixin Eloquent
 */
class Entrevista extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = 'rrhh_contratacion_entrevistas';

    protected $fillable = [
        'postulacion_id',
        'fecha_hora',
        'duracion',
        'presencial',
        'link',
        'canton_id',
        'direccion',
        'reagendada', //boolean
        'nueva_fecha_hora',
        'observacion',
        'asistio' //boolean
    ];

    //obtener la llave primaria
    public function getKeyName()
    {
        return 'postulacion_id';
    }

    /**
     * Relación uno a uno.
     * Una entrevista se emite para una postulación
     */
    public function postulacion(){
        return $this->belongsTo(Postulacion::class, 'postulacion_id');
    }
}
