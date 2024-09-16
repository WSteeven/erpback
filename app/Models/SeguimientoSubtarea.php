<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

// pendiente de borrar
/**
 * App\Models\SeguimientoSubtarea
 *
 * @property int $id
 * @property array|null $observaciones
 * @property array|null $materiales_tarea_ocupados
 * @property array|null $materiales_stock_ocupados
 * @property array|null $materiales_devolucion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArchivoSeguimiento> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Subtarea|null $subtarea
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TrabajoRealizado> $trabajoRealizado
 * @property-read int|null $trabajo_realizado_count
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea whereMaterialesDevolucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea whereMaterialesStockOcupados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea whereMaterialesTareaOcupados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoSubtarea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeguimientoSubtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait, Filterable;

    // Seguimiento subtarea
    protected $table = 'seguimientos';

    protected $fillable = [
        'observaciones',
        'materiales_tarea_ocupados',
        'materiales_stock_ocupados',
        'materiales_devolucion',
    ];

    protected $casts = [
        'observaciones' => 'json',
        'materiales_tarea_ocupados' => 'json',
        'materiales_stock_ocupados' => 'json',
        'materiales_devolucion' => 'json',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];

    public function trabajoRealizado()
    {
        return $this->hasMany(TrabajoRealizado::class, 'seguimiento_id', 'id');
    }

    // Relacion uno a muchos
    public function archivos()
    {
        return $this->hasMany(ArchivoSeguimiento::class, 'seguimiento_id', 'id');
    }

    public function subtarea()
    {
        return $this->hasOne(Subtarea::class, 'seguimiento_id', 'id');
    }
}
