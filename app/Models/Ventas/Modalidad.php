<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Ventas\Modalidad
 *
 * @property int $id
 * @property string $nombre
 * @property int $umbral_minimo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad query()
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad whereUmbralMinimo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modalidad whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Modalidad extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_modalidades';
    protected $fillable =['nombre','umbral_minimo'];
    private static $whiteListFilter = [
        '*',
    ];
}
