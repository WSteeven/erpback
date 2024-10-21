<?php

namespace App\Models\RecursosHumanos\Alimentacion;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\RecursosHumanos\Alimentacion\Alimentacion
 *
 * @property int $id
 * @property string $nombre
 * @property string $mes
 * @property bool $finalizado
 * @property bool $es_quincena
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion whereEsQuincena($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion whereFinalizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion whereMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alimentacion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Alimentacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_alimentaciones';
    protected $fillable = [
        'nombre',
        'mes',
        'finalizado',
        'es_quincena'
    ];
    private static $whiteListFilter = [
        'nombre',
        'empleado',
        'mes',
        'finalizado',
        'es_quincena'
    ];
    protected $casts = ['finalizado' => 'boolean', 'es_quincena' => 'boolean'];
}
