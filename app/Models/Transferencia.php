<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Transferencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;

    protected $table = "transferencias";
    protected $fillable = [
        'justificacion',
        'sucursal_salida_id',
        'sucursal_destino_id',
        'solicitante_id',
        'cliente_id',
        'autorizacion_id',
        'per_autoriza_id',
        'recibida',
        'estado',
        'observacion_aut',
        'observacion_est',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const PENDIENTE = "PENDIENTE";
    const TRANSITO = "TRANSITO";
    const COMPLETADO = "COMPLETADO";

    private static $whiteListFilter = ['*'];


    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación muchos a muchos(inversa).
     * Una transferencia tiene varios items del inventario
     */
    public function items()
    {
        return $this->belongsToMany(Inventario::class, 'detalle_inventario_transferencia', 'transferencia_id', 'inventario_id')
            ->withPivot(['cantidad'])->withTimestamps();
    }

    /**
     * Relacion uno a uno(inversa).
     * Una o varias transferencias salen de una sucursal
     */
    public function sucursalSalida()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a uno(inversa).
     * Una o varias transferencias llegan a una sucursal
     */
    public function sucursalDestino()
    {
        return $this->belongsTo(Sucursal::class);
    }
    /**
     * Relacion uno a muchos (inversa).
     * Una o varias transferencias pertenece a un solicitante que debe ser un bodeguero
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transferencia
     */
    public function autoriza()
    {
        return $this->belongsTo(Empleado::class, 'per_autoriza_id', 'id');
    }

    /**
     * Relación uno a uno(inversa).
     * Una o varias transferencias solo pueden tener una autorización.
     */
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    /**
     * Relación uno a uno(inversa).
     * Una o varias transferencias solo pueden tener una autorización.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    /**
     * Obtener el listados de productos de un traspaso
     */
    public static function listadoProductos(int $id)
    {
        $items = Transferencia::find($id)->items()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($items as $item) {
            // Log::channel('testing')->info('Log', ['Foreach de traspaso:', $item]);
            /* $detalle = DetalleInventarioTransferencia::withSum('devoluciones', 'cantidad')
            ->where('traspaso_id', $item->pivot->traspaso_id)
            ->where('inventario_id', $item->pivot->inventario_id)->first(); */
            $row['id'] = $item->id;
            $row['producto'] = $item->detalle->producto->nombre;
            $row['detalle_id'] = $item->detalle->id;
            $row['descripcion'] = $item->detalle->descripcion;
            $row['categoria'] = $item->detalle->producto->categoria->nombre;
            $row['cliente_id'] = $item->cliente->empresa->razon_social;
            $row['condicion'] = $item->condicion->nombre;
            $row['cantidad'] = $item->pivot->cantidad;
            $row['cantidades'] = $item->pivot->cantidad;
            $row['devolucion'] = null;
            // $row['devuelto'] = $detalle->devoluciones_sum_cantidad;
            $results[$id] = $row;
            $id++;
        }
        // Log::channel('testing')->info('Log', ['Foreach de movimientos de devoluciones del  traspaso:', $devoluciones]);
        return $results;
    }
}
