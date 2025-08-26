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
use Laravel\Scout\EngineManager;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Vehiculos\Servicio
 *
 * @method static find($servicio_id)
 * @property int $id
 * @property string $nombre
 * @property string $tipo
 * @property int|null $notificar_antes
 * @property int|null $intervalo
 * @property bool $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|Servicio acceptRequest(?array $request = null)
 * @method static Builder|Servicio filter(?array $request = null)
 * @method static Builder|Servicio ignoreRequest(?array $request = null)
 * @method static Builder|Servicio newModelQuery()
 * @method static Builder|Servicio newQuery()
 * @method static Builder|Servicio query()
 * @method static Builder|Servicio setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Servicio setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Servicio setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Servicio whereCreatedAt($value)
 * @method static Builder|Servicio whereEstado($value)
 * @method static Builder|Servicio whereId($value)
 * @method static Builder|Servicio whereIntervalo($value)
 * @method static Builder|Servicio whereNombre($value)
 * @method static Builder|Servicio whereNotificarAntes($value)
 * @method static Builder|Servicio whereTipo($value)
 * @method static Builder|Servicio whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Servicio extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;
    use Searchable;

    protected $table = 'veh_servicios';
    protected $fillable = [
        'nombre',
        'tipo',
        'notificar_antes',
        'intervalo',
        'estado',
    ];
    const PREVENTIVO = 'PREVENTIVO';
    const CORRECTIVO = 'CORRECTIVO';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        return [
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
        ];
    }

    public function searchableUsing()
    {
        return app(EngineManager::class)->engine('database');
    }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
}
