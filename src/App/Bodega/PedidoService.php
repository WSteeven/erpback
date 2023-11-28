<?php

namespace Src\App\Bodega;

use App\Http\Resources\PedidoResource;
use App\Models\Autorizacion;
use App\Models\Pedido;
use App\Models\Sucursal;
use Exception;
use Illuminate\Support\Facades\Log;
use Src\Config\Autorizaciones;
use Src\Config\EstadosTransacciones;

class PedidoService
{
    public function __construct()
    {
    }

    public static function filtrarPedidosReporte($request)
    {
        $results = [];
        switch ($request->estado) {
            case 1: //pendientes
                $results = Pedido::where('autorizacion_id', Autorizaciones::PENDIENTE)
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))
                    ->when($request->fecha_fin, function ($query) use ($request) {
                        $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                    })->get();
                break;
            case 2: //completos
                $results = Pedido::where('estado_id', EstadosTransacciones::COMPLETA)
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))
                    ->when($request->fecha_fin, function ($query) use ($request) {
                        $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                    })->get();
                break;
            case 3: //parciales
                $results = Pedido::where('estado_id', EstadosTransacciones::PARCIAL)
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))
                    ->when($request->fecha_fin, function ($query) use ($request) {
                        $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                    })->get();
                break;
            case 4: // anulados
                $results = Pedido::where('autorizacion_id', Autorizaciones::CANCELADO)
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))
                    ->when($request->fecha_fin, function ($query) use ($request) {
                        $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                    })->get();
                break;
            default: //todos los estados
                $results = Pedido::where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))->when($request->fecha_fin, function ($query) use ($request) {
                    $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                })->get();
        }
        return $results;
    }

    public static function empaquetarDatos($datos)  {
        $results = [];
        $cont =0;
        foreach($datos as $d){
            $row['pedido_id'] = $d['id'];
            $row['created_at'] = $d['created_at'];
            $row['justificacion'] = $d['justificacion'];
            $row['solicitante'] = $d['solicitante']->nombres . ' ' . $d['solicitante']->apellidos;
            $row['autorizacion'] = $d['autorizacion']->nombre;
            $row['autorizador'] = $d['autoriza']->nombres . ' ' .$d['autoriza']->apellidos;
            $row['estado'] = $d['estado']->nombre;
            $row['responsable'] = $d['responsable']->nombres . ' ' .$d['responsable']->apellidos;
            foreach($d->listadoProductos($d["id"]) as $p){
                $row['descripcion'] = $p['descripcion'];
                $row['serial'] = $p['serial'];
                $row['categoria'] = $p['categoria'];
                $row['cantidad'] = $p['cantidad'];
                $row['despachado'] = $p['despachado'];
            }
            $results[$cont]  =$row;
            $cont++;
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
