<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Pedido extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'pedidos';
    public $fillable = [
        'justificacion',
        'fecha_limite',
        'observacion_aut',
        'observacion_est',
        'solicitante_id',
        'autorizacion_id',
        'per_autoriza_id',
        'tarea_id',
        'sucursal_id',
        'estado_id',
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
    /**
     * Relación muchos a muchos(inversa).
     * Un pedido tiene varios detalles
     */
    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'detalle_pedido_producto', 'pedido_id', 'detalle_id')
            ->withPivot('cantidad', 'despachado')->withTimestamps();
    }


    /**
     * Relación uno a muchos(inversa).
     * Un pedido pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Uno o varios pedidos pertenecen a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos pertenece a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos son autorizados por una persona
     */
    public function autoriza()
    {
        return $this->belongsTo(Empleado::class, 'per_autoriza_id', 'id');
    }

    /**
     * Relación uno a uno(inversa).
     * Uno o varios pedidos solo pueden tener una autorización.
     */
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    /**
     * Relación uno a uno(inversa).
     * Uno o varios pedidos solo pueden tener una autorización.
     */
    public function estado()
    {
        return $this->belongsTo(EstadoTransaccion::class);
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    /**
     * Obtener el listados de productos de un pedido
     */
    public static function listadoProductos(int $id)
    {
        $detalles = Pedido::find($id)->detalles()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($detalles as $detalle) {
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['cantidad'] = $detalle->pivot->cantidad;
            $row['despachado'] = $detalle->pivot->despachado;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }

    /**
     * Filtrar todos los pedidos de un empleado o coordinador de acuerdo al estado de una autorizacion.
     * @param string $estado
     * @return array $results Resultados filtrados
     */
    public static function filtrarPedidosEmpleado($estado)
    {
        $autorizacion = Autorizacion::where('nombre', $estado)->first();
        $results = [];
        if ($autorizacion) {
            $results = Pedido::where('autorizacion_id', $autorizacion->id)
                ->where(function ($query) {
                    $query->where('solicitante_id',  auth()->user()->empleado->id)
                        ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                })->get();
        }
        return $results;
    }

    
    /**
     * Filtrar todos los pedidos para el bodeguero, de acuerdo al estado de una autorizacion.
     * @param string $estado
     * @return array $results Resultados filtrados
     */
    public static function filtrarPedidosBodeguero($estado)
    {
        $autorizacion = Autorizacion::where('nombre', $estado)->first();
        $estadoTransaccion = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        $results = [];
        if ($autorizacion) {
            $results = Pedido::where('autorizacion_id', $autorizacion->id)->where('estado_id', '!=', $estadoTransaccion->id)->get();
            return $results;
        } elseif ($estado ===$estadoTransaccion->nombre) {
            $results = Pedido::where('estado_id', $estadoTransaccion->id)->get();
            return $results;
        } else {
            $results = Pedido::all();
            return $results;
        }
    }
}
