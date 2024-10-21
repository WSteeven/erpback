<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\DescuentosLey
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey query()
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosLey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DescuentosLey extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'descuento_ley';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
    ];
}
