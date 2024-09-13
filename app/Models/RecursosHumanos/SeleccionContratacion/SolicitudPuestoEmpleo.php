<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Autorizacion;
use App\Models\Cargo;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\SolicitudPuestoEmpleo
 *
 * @property int $id
 * @property string $descripcion
 * @property int $anos_experiencia
 * @property int $tipo_puesto_id
 * @property int $cargo_id
 * @property int $autorizacion_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Autorizacion|null $autorizacion
 * @property-read Cargo|null $cargo
 * @property-read \App\Models\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajo|null $tipoPuesto
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo whereAnosExperiencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo whereAutorizacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo whereTipoPuestoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPuestoEmpleo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SolicitudPuestoEmpleo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_solicitudes_puestos_empleos';
    protected $fillable = [
        'descripcion',
        'anos_experiencia',
        'tipo_puesto_id',
        'cargo_id',
        'autorizacion_id'

    ];
    private static $whiteListFilter = [
        'descripcion',
        'anos_experiencia',
        'tipo_puesto_id',
        'tipo_puesto',
        'cargo_id',
        'cargo',
        'autorizacion_id',
        'autorizacion'
    ];
    public function tipoPuesto(){
        return $this->hasOne(TipoPuestoTrabajo::class,'id', 'tipo_puesto_id');
    }
    public function cargo(){
        return $this->hasOne(Cargo::class,'id', 'cargo_id');
    }
    public function autorizacion(){
        return $this->hasOne(Autorizacion::class,'id', 'autorizacion_id');
    }
}
