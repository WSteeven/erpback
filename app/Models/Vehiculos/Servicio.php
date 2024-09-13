<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio query()
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio whereIntervalo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio whereNotificarAntes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Servicio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Servicio extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable, Searchable;

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

    private static $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        return [
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
        ];
    }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
}
