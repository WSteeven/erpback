<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\TipoLicencia
 *
 * @property int $id
 * @property string $nombre
 * @property int $num_dias
 * @property bool|null $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia whereNumDias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoLicencia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoLicencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'tipo_licencias';
    protected $fillable = [
        'nombre',
        'num_dias',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean',
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
        'estado',
    ];
}
