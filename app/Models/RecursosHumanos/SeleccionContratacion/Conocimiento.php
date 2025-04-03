<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Cargo;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\Conocimiento
 *
 * @method static whereIn(string $string, array $array_map)
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Cargo|null $cargo
 * @method static Builder|Conocimiento acceptRequest(?array $request = null)
 * @method static Builder|Conocimiento filter(?array $request = null)
 * @method static Builder|Conocimiento ignoreRequest(?array $request = null)
 * @method static Builder|Conocimiento newModelQuery()
 * @method static Builder|Conocimiento newQuery()
 * @method static Builder|Conocimiento query()
 * @method static Builder|Conocimiento setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Conocimiento setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Conocimiento setLoadInjectedDetection($load_default_detection)
 * @property int $id
 * @property int $cargo_id
 * @property string $nombre
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Conocimiento whereActivo($value)
 * @method static Builder|Conocimiento whereCargoId($value)
 * @method static Builder|Conocimiento whereCreatedAt($value)
 * @method static Builder|Conocimiento whereId($value)
 * @method static Builder|Conocimiento whereNombre($value)
 * @method static Builder|Conocimiento whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Conocimiento extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_contratacion_conocimientos';
    protected $fillable = [
        'cargo_id',
        'nombre',
        'activo'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa)
     * Un conocimiento pertenece a un cargo y un cargo tiene varios conocimientos.
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
}
