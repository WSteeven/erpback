<?php

namespace App\Models\ActivosFijos;

use App\Events\ActivosFijos\NotificarActivoFijoEntregadoEvent;
use App\Events\ActivosFijos\NotificarEntregaActivoFijoEvent;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\TransaccionBodega;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;

class ActivoFijo extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel, Searchable;

    protected $table = 'af_activos_fijos';
    protected $fillable = [
        'detalle_producto_id',
        'cliente_id',
    ];

    private static $whiteListFilter = ['*'];

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
     *************/
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
        } catch (\Throwable $th) {
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
