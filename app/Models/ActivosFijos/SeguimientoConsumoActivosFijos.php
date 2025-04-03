<?php

namespace App\Models\ActivosFijos;

use App\Models\Archivo;
use App\Models\Canton;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Laravel\Scout\Searchable;

/**
 * App\Models\ActivosFijos\SeguimientoConsumoActivosFijos
 *
 * @property int $id
 * @property int $stock_actual
 * @property int $cantidad_utilizada
 * @property string|null $observacion
 * @property int $empleado_id
 * @property int $detalle_producto_id
 * @property int|null $cliente_id
 * @property int|null $canton_id
 * @property int|null $motivo_consumo_activo_fijo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $se_reporto_sicosep
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @property-read Cliente|null $cliente
 * @property-read DetalleProducto $detalleProducto
 * @property-read Empleado $empleado
 * @property-read \App\Models\ActivosFijos\MotivoConsumoActivoFijo|null $motivoConsumoActivoFijo
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos responsable()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereCantidadUtilizada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereDetalleProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereMotivoConsumoActivoFijoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereSeReportoSicosep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereStockActual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoConsumoActivosFijos whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeguimientoConsumoActivosFijos extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel, Searchable;

    protected $table = 'af_seguimientos_consumo_activos_fijos';
    protected $fillable = [
        'stock_actual',
        'cantidad_utilizada',
        'empleado_id',
        'detalle_producto_id',
        'cliente_id',
        'canton_id',
        'motivo_consumo_activo_fijo_id',
        'observacion',
        'se_reporto_sicosep'
    ];

    private static $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        // return $this->toArray();
        return [
            'canton_id' => $this->canton?->canton,
            'motivo_consumo' => $this->motivoConsumoActivoFijo?->nombre,
        ]; 
    }

    protected $casts = ['se_reporto_sicosep' => 'boolean'];

    /***********************
     * Constantes archivos
     ***********************/
    const JUSTIFICATIVO_USO = 'JUSTIFICATIVO USO';

    /*************
     * Relaciones
     *************/
    public function motivoConsumoActivoFijo()
    {
        return $this->belongsTo(MotivoConsumoActivoFijo::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
