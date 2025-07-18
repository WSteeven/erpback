<?php

namespace App\Models\ActivosFijos;

use App\Events\ActivosFijos\NotificarEntregaActivoFijoEvent;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\TransaccionBodega;
use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Models\Audit;
use Throwable;

/**
 * App\Models\ActivosFijos\ActivoFijo
 *
 * @property int $id
 * @property int $detalle_producto_id
 * @property int $cliente_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Cliente|null $cliente
 * @property-read DetalleProducto|null $detalleProducto
 * @method static Builder|ActivoFijo acceptRequest(?array $request = null)
 * @method static Builder|ActivoFijo filter(?array $request = null)
 * @method static Builder|ActivoFijo ignoreRequest(?array $request = null)
 * @method static Builder|ActivoFijo newModelQuery()
 * @method static Builder|ActivoFijo newQuery()
 * @method static Builder|ActivoFijo query()
 * @method static Builder|ActivoFijo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ActivoFijo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ActivoFijo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ActivoFijo whereClienteId($value)
 * @method static Builder|ActivoFijo whereCreatedAt($value)
 * @method static Builder|ActivoFijo whereDetalleProductoId($value)
 * @method static Builder|ActivoFijo whereId($value)
 * @method static Builder|ActivoFijo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ActivoFijo extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel, Searchable;

    protected $table = 'af_activos_fijos';
    protected $fillable = [
        'codigo_personalizado',
        'codigo_sistema_anterior',
        'detalle_producto_id',
        'cliente_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
        ];
    }


    /**************
     * Relaciones
     **************/
    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class, 'detalle_producto_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /*************
     * Funciones
     ************
     * @throws Exception
     */
    public static function cargarComoActivo(DetalleProducto $detalle_producto, int|null $cliente_id)
    {
        try {
            if (!$detalle_producto->esActivo) return;

            $existe = ActivoFijo::where('detalle_producto_id', $detalle_producto->id)->where('cliente_id', $cliente_id)->exists();

            if ($existe) return;

            ActivoFijo::create([
                'detalle_producto_id' => $detalle_producto->id,
                'cliente_id' => $cliente_id,
            ]);
        } catch (Throwable $th) {
            throw new Exception($th->getMessage() . '. ' . $th->getLine());
        }
    }

    public static function notificarEntregaActivos(DetalleProducto $detalle_producto, TransaccionBodega $transaccion_bodega)
    {
        if (!$detalle_producto->esActivo) return;

        $users_id = User::role([User::ROL_CONTABILIDAD])->pluck('id');
        $empleados = Empleado::whereIn('usuario_id', $users_id)->habilitado()->get();

        $descripcion_detalle_producto = $detalle_producto->descripcion  . ' ' . $detalle_producto->serial;

        foreach ($empleados as $empleado) {
            event(new NotificarEntregaActivoFijoEvent($transaccion_bodega, $empleado->id, $descripcion_detalle_producto));
        }
    }
}
