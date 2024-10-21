<?php

namespace App\Models\RecursosHumanos;

use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\TipoDiscapacidad
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read int|null $empleados_count
 * @method static Builder|TipoDiscapacidad acceptRequest(?array $request = null)
 * @method static Builder|TipoDiscapacidad filter(?array $request = null)
 * @method static Builder|TipoDiscapacidad ignoreRequest(?array $request = null)
 * @method static Builder|TipoDiscapacidad newModelQuery()
 * @method static Builder|TipoDiscapacidad newQuery()
 * @method static Builder|TipoDiscapacidad query()
 * @method static Builder|TipoDiscapacidad setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|TipoDiscapacidad setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|TipoDiscapacidad setLoadInjectedDetection($load_default_detection)
 * @method static Builder|TipoDiscapacidad whereCreatedAt($value)
 * @method static Builder|TipoDiscapacidad whereId($value)
 * @method static Builder|TipoDiscapacidad whereNombre($value)
 * @method static Builder|TipoDiscapacidad whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TipoDiscapacidad extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'rrhh_tipos_discapacidades';
    protected $fillable = ['nombre'];

    private static array $whiteListFilter = [" *"];

}
