<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Vehiculos\Licencia
 *
 * @property int $id
 * @property int $conductor_id
 * @property string $tipo_licencia
 * @property string $inicio_vigencia
 * @property string $fin_vigencia
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Conductor|null $conductor
 * @method static Builder|Licencia acceptRequest(?array $request = null)
 * @method static Builder|Licencia filter(?array $request = null)
 * @method static Builder|Licencia ignoreRequest(?array $request = null)
 * @method static Builder|Licencia newModelQuery()
 * @method static Builder|Licencia newQuery()
 * @method static Builder|Licencia query()
 * @method static Builder|Licencia setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Licencia setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Licencia setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Licencia whereConductorId($value)
 * @method static Builder|Licencia whereCreatedAt($value)
 * @method static Builder|Licencia whereFinVigencia($value)
 * @method static Builder|Licencia whereId($value)
 * @method static Builder|Licencia whereInicioVigencia($value)
 * @method static Builder|Licencia whereTipoLicencia($value)
 * @method static Builder|Licencia whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Licencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'veh_licencias';
    protected $fillable = [
        'tipo_licencia',
        'inicio_vigencia',
        'fin_vigencia',
        'conductor_id'
    ];

    public function conductor()
    {
        return $this->belongsTo(Conductor::class);
    }

    public static function eliminarObsoletos($conductor_id, $tiposEncontrados)
    {
        $itemsNoEncontrados = Licencia::where('conductor_id', $conductor_id)
            ->whereNotIn('tipo_licencia', $tiposEncontrados)
            ->delete();
        Log::channel('testing')->info('Log', ['Eliminados', $itemsNoEncontrados]);
    }
}
