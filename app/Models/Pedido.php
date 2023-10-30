<?php

namespace App\Models;

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
        'tarea_id',
        'sucursal_id',
        'estado_id',
        'evidencia1',
        'evidencia2',
        'per_retira_id',
        'cliente_id',
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
        $solicitante=null;
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

    /**
     * Filtrar todos los pedidos de un empleado o coordinador de acuerdo al estado de una autorizacion.
     * @param string $estado
     * @return array $results Resultados filtrados
     */
    public static function filtrarPedidosEmpleado($estado)
    {
        $results = [];
        try {
            $autorizacion = Autorizacion::where('nombre', $estado)->first();
            switch ($estado) {
                case 'PENDIENTE': //cuando el pedido está PENDIENTE de autorización
                    return Pedido::where('autorizacion_id', $autorizacion->id)->where(function ($query) {
                        $query->where('solicitante_id',  auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id);
                    })->orderBy('id', 'DESC')->get();
                    break;
                case  'APROBADO': // cuando el pedido está con autorización APROBADO y pendiente de despacho
                    return Pedido::where('autorizacion_id', $autorizacion->id)->where('estado_id', '=',  EstadosTransacciones::PENDIENTE)->where(function ($query) {
                        $query->where('solicitante_id',  auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id);
                    })->orderBy('id', 'DESC')->get();
                    break;
                case 'PARCIAL': //cuando el pedido está con autorización aprobado y despacho PARCIAL
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::PARCIAL)->where(function ($query) {
                        $query->where('solicitante_id',  auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id);
                    })->orderBy('id', 'DESC')->get();
                    break;
                case 'COMPLETA': //cuando el pedido está con estado de despacho COMPLETA
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::COMPLETA)->where(function ($query) {
                        $query->where('solicitante_id',  auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id);
                    })->orderBy('id', 'DESC')->get();
                    break;
                case 'CANCELADO': // cuando el pedido está con autorización CANCELADO
                    return Pedido::where('autorizacion_id', $autorizacion->id)->where(function ($query) {
                        $query->where('solicitante_id',  auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id);
                    })->orderBy('id', 'DESC')->get();
                    break;
                default:
                    return $results;
            }
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Error al filtrar:', $ex]);
        }
    }
    public static function filtrarPedidosBodegueroTelconet($estado)
    {
        $results = [];
        try {
            $idsSucursalesTelconet = Sucursal::where('lugar', 'LIKE', '%telconet%')->get('id');
            $autorizacion = Autorizacion::where('nombre', $estado)->first();
            switch ($estado) {
                case 'PENDIENTE': //cuando el pedido está PENDIENTE de autorización
                    return Pedido::where('autorizacion_id', $autorizacion->id)->where(function ($query) use ($idsSucursalesTelconet) {
                        $query->where('solicitante_id', auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id)
                            ->orwhereIn('sucursal_id', $idsSucursalesTelconet);
                    })->orderBy('id', 'DESC')->get();
                    break;
                case  'APROBADO': // cuando el pedido está con autorización APROBADO y pendiente de despacho
                    return Pedido::where('autorizacion_id', $autorizacion->id)->where('estado_id', '=',  EstadosTransacciones::PENDIENTE)->where(function ($query) use ($idsSucursalesTelconet) {
                        $query->where('solicitante_id', auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id)
                            ->orwhereIn('sucursal_id', $idsSucursalesTelconet);
                    })->orderBy('id', 'DESC')->get();
                    break;
                case 'PARCIAL': //cuando el pedido está con autorización aprobado y despacho PARCIAL
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::PARCIAL)->where(function ($query) use ($idsSucursalesTelconet) {
                        $query->where('solicitante_id', auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id)
                            ->orwhereIn('sucursal_id', $idsSucursalesTelconet);
                    })->orderBy('id', 'DESC')->get();
                    break;
                case 'COMPLETA': //cuando el pedido está con estado de despacho COMPLETA
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::COMPLETA)->where(function ($query) use ($idsSucursalesTelconet) {
                        $query->where('solicitante_id', auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id)
                            ->orwhereIn('sucursal_id', $idsSucursalesTelconet);
                    })->orderBy('id', 'DESC')->get();
                    break;
                case 'CANCELADO': // cuando el pedido está con autorización CANCELADO
                    return Pedido::where('autorizacion_id', $autorizacion->id)->where(function ($query) use ($idsSucursalesTelconet) {
                        $query->where('solicitante_id', auth()->user()->empleado->id)
                            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                            ->orWhere('responsable_id', auth()->user()->empleado->id)
                            ->orwhereIn('sucursal_id', $idsSucursalesTelconet);
                    })->orderBy('id', 'DESC')->get();
                    break;
                default:
                    return $results;
            }
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Error al filtrar:', $ex]);
        }

        // $idsSucursalesTelconet = Sucursal::where('lugar', 'LIKE', '%telconet%')->get('id');
        // $autorizacion = Autorizacion::where('nombre', $estado)->first();
        // $estadoTransaccion = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        // $results = [];
        // if ($autorizacion) {
        //     $results = Pedido::where('autorizacion_id', $autorizacion->id)->where('estado_id', '!=', $estadoTransaccion->id)->where(function ($query) use($idsSucursalesTelconet) {
        //         $query->where('solicitante_id', auth()->user()->empleado->id)
        //             ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
        //             ->orWhere('responsable_id', auth()->user()->empleado->id)
        //             ->orwhereIn('sucursal_id', $idsSucursalesTelconet);
        //     })->orderBy('id', 'DESC')->get();
        // } elseif ($estado === $estadoTransaccion->nombre) {
        //     $results = Pedido::where('estado_id', $estadoTransaccion->id)->where(function ($query) use($idsSucursalesTelconet) {
        //         $query->where('solicitante_id', auth()->user()->empleado->id)
        //             ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
        //             ->orWhere('responsable_id', auth()->user()->empleado->id)
        //             ->orwhereIn('sucursal_id', $idsSucursalesTelconet);
        //     })->orderBy('id', 'DESC')->get();
        //     return $results;
        // } else {
        //     $results = Pedido::whereIn('sucursal_id', $idsSucursalesTelconet)->orderBy('id', 'DESC')->get();
        //     return $results;
        // }


        // return $results;
    }


    /**
     * Filtrar todos los pedidos para el bodeguero, de acuerdo al estado de una autorizacion.
     * @param string $estado
     * @return array $results Resultados filtrados
     */
    public static function filtrarPedidosBodeguero($estado)
    {
        $results = [];
        try {
            $autorizacion = Autorizacion::where('nombre', $estado)->first();
            switch ($estado) {
                case 'PENDIENTE': //cuando el pedido está PENDIENTE de autorización
                    return Pedido::where('autorizacion_id', $autorizacion->id)->orderBy('id', 'DESC')->get();
                    break;
                case  'APROBADO': // cuando el pedido está con autorización APROBADO y pendiente de despacho
                    return Pedido::where('autorizacion_id', $autorizacion->id)->where('estado_id', '=',  EstadosTransacciones::PENDIENTE)->orderBy('id', 'DESC')->get();
                    break;
                case 'PARCIAL': //cuando el pedido está con autorización aprobado y despacho PARCIAL
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::PARCIAL)->orderBy('id', 'DESC')->get();
                    break;
                case 'COMPLETA': //cuando el pedido está con estado de despacho COMPLETA
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::COMPLETA)->orderBy('id', 'DESC')->get();
                    break;
                case 'CANCELADO': // cuando el pedido está con autorización CANCELADO
                    return Pedido::where('autorizacion_id', $autorizacion->id)->orderBy('id', 'DESC')->get();
                    break;
                default:
                    return $results;
            }
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Error al filtrar:', $ex]);
        }
    }

    /**
     * Filtrar todos los pedidos para el de activos fijos de acuerdo al estado de una autorizacion
     */
    public static function filtrarPedidosActivosFijos($estado)
    {
        $results = [];
        try {
            $autorizacion = Autorizacion::where('nombre', $estado)->first();
            switch ($estado) {
                case 'PENDIENTE': //cuando el pedido está PENDIENTE de autorización
                    return Pedido::where('autorizacion_id', $autorizacion->id)->orderBy('id', 'DESC')->get();
                    break;
                case  'APROBADO': // cuando el pedido está con autorización APROBADO y pendiente de despacho
                    return Pedido::where('autorizacion_id', $autorizacion->id)->where('estado_id', '=',  EstadosTransacciones::PENDIENTE)->orderBy('id', 'DESC')->get();
                    break;
                case 'PARCIAL': //cuando el pedido está con autorización aprobado y despacho PARCIAL
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::PARCIAL)->orderBy('id', 'DESC')->get();
                    break;
                case 'COMPLETA': //cuando el pedido está con estado de despacho COMPLETA
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::COMPLETA)->orderBy('id', 'DESC')->get();
                    break;
                case 'CANCELADO': // cuando el pedido está con autorización CANCELADO
                    return Pedido::where('autorizacion_id', $autorizacion->id)->orderBy('id', 'DESC')->get();
                    break;
                default:
                    return $results;
            }
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Error al filtrar:', $ex]);
        }
    }

    /**
     * Filtrar pedidos Administrador
     */
    public static function filtrarPedidosAdministrador($estado)
    {
        $results = [];
        try {
            $autorizacion = Autorizacion::where('nombre', $estado)->first();
            switch ($estado) {
                case 'PENDIENTE': //cuando el pedido está PENDIENTE de autorización
                    return Pedido::where('autorizacion_id', $autorizacion->id)->orderBy('id', 'DESC')->get();
                    break;
                case  'APROBADO': // cuando el pedido está con autorización APROBADO y pendiente de despacho
                    return Pedido::where('autorizacion_id', $autorizacion->id)->where('estado_id', '=',  EstadosTransacciones::PENDIENTE)->orderBy('id', 'DESC')->get();
                    break;
                case 'PARCIAL': //cuando el pedido está con autorización aprobado y despacho PARCIAL
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::PARCIAL)->orderBy('id', 'DESC')->get();
                    break;
                case 'COMPLETA': //cuando el pedido está con estado de despacho COMPLETA
                    return  Pedido::where('estado_id', '=',  EstadosTransacciones::COMPLETA)->orderBy('id', 'DESC')->get();
                    break;
                case 'CANCELADO': // cuando el pedido está con autorización CANCELADO
                    return Pedido::where('autorizacion_id', $autorizacion->id)->orderBy('id', 'DESC')->get();
                    break;
                default:
                    return $results;
            }
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Error al filtrar:', $ex]);
        }
    }
}
