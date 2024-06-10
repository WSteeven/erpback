<?php

namespace App\Models;

use App\Models\ComprasProveedores\OrdenCompra;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use Src\Config\EstadosTransacciones;

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
        'observacion_bodega',
        'observacion_aut',
        'observacion_est',
        'solicitante_id',
        'responsable_id',
        'autorizacion_id',
        'per_autoriza_id',
        'sucursal_id',
        'estado_id',
        'evidencia1',
        'evidencia2',
        'per_retira_id',
        'cliente_id',
        'proyecto_id',
        'etapa_id',
        'tarea_id',
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
            ->withPivot('cantidad', 'despachado', 'solicitante_id')->withTimestamps();
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
     * Uno o varios pedidos tienen un responsable
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos son retirados por una persona
     */
    public function retira()
    {
        return $this->belongsTo(Empleado::class, 'per_retira_id', 'id');
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
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos son autorizados por una persona
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
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
     * Relación uno a muchos.
     * Un pedido esta en varias trasacciones.
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relación polimorfica a una notificación.
     * Un pedido puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
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
        $solicitante = null;
        $id = 0;
        $row = [];
        foreach ($detalles as $detalle) {
            if ($detalle->pivot->solicitante_id) $solicitante = Empleado::find($detalle->pivot->solicitante_id);
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['serial'] = $detalle->serial;
            $row['cantidad'] = $detalle->pivot->cantidad;
            $row['despachado'] = $detalle->pivot->despachado;
            $row['solicitante'] = $solicitante?->nombres . ' ' . $solicitante?->apellidos;
            $row['solicitante_id'] = $solicitante?->id;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }
    public static function estadoOC($id)
    {
        $orden = OrdenCompra::where('pedido_id', $id)->orderBy('created_at', 'desc')->first();
        if ($orden) {
            // return $orden->estado->nombre;
            return $orden->estado->nombre . '. ' . ($orden->realizada ? 'REALIZADA' : 'PENDIENTE DE REALIZAR');
        } else
            return '';
    }
}
