<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\TipoPuestoTrabajo
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoPuestoTrabajo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoPuestoTrabajo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_tipos_puestos_trabajos';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = [
        'nombre'
    ];
}
