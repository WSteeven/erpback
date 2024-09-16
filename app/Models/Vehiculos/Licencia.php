<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\Licencia
 *
 * @property int $id
 * @property int $conductor_id
 * @property string $tipo_licencia
 * @property string $inicio_vigencia
 * @property string $fin_vigencia
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Vehiculos\Conductor|null $conductor
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia whereConductorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia whereFinVigencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia whereInicioVigencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia whereTipoLicencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Licencia whereUpdatedAt($value)
 * @mixin \Eloquent
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
