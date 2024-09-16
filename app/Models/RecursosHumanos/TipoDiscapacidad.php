<?php

namespace App\Models\RecursosHumanos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\TipoDiscapacidad
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Empleado> $empleados
 * @property-read int|null $empleados_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDiscapacidad whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoDiscapacidad extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'rrhh_tipos_discapacidades';
    protected $fillable = ['nombre'];

    private static $whiteListFilter = [" *"];

    public function empleados(){
        return $this->belongsToMany(Empleado::class,'rrhh_empleado_tipo_discapacidad_porcentaje');
    }
}
