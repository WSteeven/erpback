<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\DetalleInventarioTransferencia
 *
 * @property int $id
 * @property int $transferencia_id
 * @property int $inventario_id
 * @property int $cantidad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia whereInventarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia whereTransferenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleInventarioTransferencia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetalleInventarioTransferencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;

    protected $table = 'detalle_inventario_transferencia';

    protected $fillable = [
        'transferencia_id',
        'inventario_id',
        'cantidad',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */


}
