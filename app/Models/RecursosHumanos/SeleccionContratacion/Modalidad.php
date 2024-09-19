<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
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
 * App\Models\RecursosHumanos\SeleccionContratacion\Modalidad
 *
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|Modalidad acceptRequest(?array $request = null)
 * @method static Builder|Modalidad filter(?array $request = null)
 * @method static Builder|Modalidad ignoreRequest(?array $request = null)
 * @method static Builder|Modalidad newModelQuery()
 * @method static Builder|Modalidad newQuery()
 * @method static Builder|Modalidad query()
 * @method static Builder|Modalidad setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Modalidad setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Modalidad setLoadInjectedDetection($load_default_detection)
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Modalidad whereActivo($value)
 * @method static Builder|Modalidad whereCreatedAt($value)
 * @method static Builder|Modalidad whereId($value)
 * @method static Builder|Modalidad whereNombre($value)
 * @method static Builder|Modalidad whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Modalidad extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'rrhh_contratacion_modalidades';
    protected $fillable = [
      'nombre',
      'activo'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo'=>'boolean',
    ];

    private static array $whiteListFilter = [
        '*',
    ];





}
