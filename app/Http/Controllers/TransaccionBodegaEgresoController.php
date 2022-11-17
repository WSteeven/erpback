<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\Autorizacion;
use App\Models\EstadoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class TransaccionBodegaEgresoController extends Controller
{
    private $entidad = 'Transacción';
    public function __construct()
    {
        $this->middleware('can:puede.ver.transacciones_egresos')->only('index', 'show');
        $this->middleware('can:puede.crear.transacciones_egresos')->only('store');
        $this->middleware('can:puede.editar.transacciones_egresos')->only('update');
        $this->middleware('can:puede.eliminar.transacciones_egresos')->only('destroy');
    }

    public function list()
    {
        $idsSeleccionados = request('ids_seleccionados');

        if (request('subtarea_id')) {
            if ($idsSeleccionados) {
                $var = array_map('intval', explode(',', $idsSeleccionados));
                Log::channel('testing')->info('Log', ['arrays', $var]);
                return TransaccionBodegaResource::collection(TransaccionBodega::whereIn('id', $var)->where()->get()); // revisar
            }
            $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->get();
            // return TransaccionBodegaResource::collection($transacciones);
            return TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransacciones($transacciones, 'COMPLETA'));
        }

        // Log::channel('testing')->info('Log', ['request en el metodo list', request('estado')]);
        if (auth()->user()->hasRole(User::ROL_COORDINADOR)) {
            $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->orWhere('per_autoriza_id', auth()->user()->empleado->id)->get();
            return TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransacciones($transacciones, request('estado')));
        }
        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            $transacciones =  TransaccionBodega::ignoreRequest(['estado'])->filter()->get();
            $transacciones = $transacciones->filter(fn ($transaccion) => $transaccion->subtipo->tipoTransaccion->tipo === 'EGRESO');

            return TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransacciones($transacciones, request('estado')));
        } else {
            $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->orWhere('solicitante_id', auth()->user()->empleado->id)->get();
            $transacciones = $transacciones->filter(fn ($transaccion) => $transaccion->subtipo->tipoTransaccion->tipo === 'EGRESO');

            return  TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransacciones($transacciones, request('estado')));
        }
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {

        $page = $request['page'];
        $offset = $request['offset'];
        $estado = $request['estado'];
        $results = [];
        $queryCoordinador = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
            ->where('solicitante_id', auth()->user()->empleado->id)
            ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
            ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
            ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
            ->where('tipos_transacciones.tipo', '=', 'EGRESO');
        $queryEmpleado = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
            ->where('solicitante_id', auth()->user()->empleado->id)
            ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
            ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
            ->where('tipos_transacciones.tipo', '=', 'EGRESO');
        $joinPendiente = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
            ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
            ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
            ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE);
        $joinAutorizacionPendiente = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
            ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
            ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
            ->where('autorizaciones.nombre', Autorizacion::PENDIENTE);
        $joinAutorizacionAprobada = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
            ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
            ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
            ->where('autorizaciones.nombre', Autorizacion::APROBADO);
        if ($page) {
            if (auth()->user()->hasRole(User::ROL_COORDINADOR)) { //si es coordinador
                switch ($estado) {
                    case 'TODO':
                        $results = $queryCoordinador->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                        break;
                    case 'ESPERA':
                        $results = $queryCoordinador
                            ->union($joinPendiente)
                            ->union($joinAutorizacionPendiente)
                            ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                        break;
                    case 'PENDIENTE':
                        Log::channel('testing')->info('Log', ['entro al PENDIENTE', $estado]);
                        $results = $queryCoordinador
                            ->union($joinPendiente)
                            ->union($joinAutorizacionAprobada)
                            ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                        break;
                    default:
                        $results = $queryCoordinador
                            ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                            ->where('estados_transacciones_bodega.nombre', $estado)
                            ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                }
            } elseif (auth()->user()->hasRole(User::ROL_BODEGA)) { //si es bodeguero
                //
            } else { //cualquier otro
                switch ($estado) {
                    case 'TODO':
                        Log::channel('testing')->info('Log', ['entro al TODO de la 142', $estado]);
                        $results = $queryEmpleado->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                        break;
                    case 'ESPERA':
                        Log::channel('testing')->info('Log', ['entro al ESPERA', $estado]);
                        $results = $queryEmpleado
                            ->union($joinPendiente)
                            ->union($joinAutorizacionPendiente)
                            ->simplePaginate($request['offset']);
                        // joinSub($joinPendiente, 'joinPendiente', function($join){
                        //     $join->on('transacciones_bodega.id', '=', 'joinPendiente.id');
                        // })->simplePaginate($request['offset']);
                        // })->joinSub($joinAutorizacion, 'joinAutorizacion', function($join){
                        //     $join->on('transacciones_bodega.id', '=', 'joinAutorizacion.id');
                        // })->simplePaginate($request['offset']);
                        // ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                        // ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                        // ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                        // ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                        // ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                        // ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                        // ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                        break;
                    case 'PENDIENTE':
                        Log::channel('testing')->info('Log', ['entro al PENDIENTE', $estado]);
                        $results = $queryEmpleado
                            ->union($joinPendiente)
                            ->union($joinAutorizacionAprobada)
                            ->simplePaginate($request['offset']);
                        // $results = $queryEmpleado
                        //     ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                        //     ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                        //     ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                        //     ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                        //     ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                        //     ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                        //     ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                        break;
                    default:
                        Log::channel('testing')->info('Log', ['entro al DEFAULT', $estado]);
                        $results = $queryEmpleado
                            ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                            ->where('estados_transacciones_bodega.nombre', $estado)
                            ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                }
            }
        } else { //si no hay paginacion
            //si es coordinador
            //si es bodeguero
            //cualquier otro
        }
        return response()->json(compact('results'));
    }


    /**
     * Listar
     */
    public function index2(Request $request)
    {
        $page = $request['page'];
        $estado = $request['estado'];
        // Log::channel('testing')->info('Log', ['vARIABLE RECIBIDA: ', $estado]);
        Log::channel('testing')->info('Log', ['id del usuario: ', auth()->user()->empleado->id]);
        $results = [];
        if ($page) {
            if (auth()->user()->hasRole(User::ROL_COORDINADOR)) {
                if ($estado) {
                    switch ($estado) {
                        case 'TODO':
                            Log::channel('testing')->info('Log', ['entro al TODO del coordinador', $estado]);
                            $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                                ->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                                ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                                ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                                ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                                ->simplePaginate($request['offset']);
                            Log::channel('testing')->info('Log', ['Datos encontrado: ', $results]);
                            TransaccionBodegaResource::collection($results);
                            $results->appends(['offset' => $request['offset']]);
                            break;
                        case 'ESPERA':
                            Log::channel('testing')->info('Log', ['entro al ESPERA del coordinador', $estado]);
                            $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                                ->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                                ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                                ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                                ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                                ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                                ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                                ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                                ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                                ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                                ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                                ->simplePaginate($request['offset']);
                            TransaccionBodegaResource::collection($results);
                            $results->appends(['offset' => $request['offset']]);
                            break;
                        case 'PENDIENTE':
                            Log::channel('testing')->info('Log', ['entro al PENDIENTE del coordinador', $estado]);
                            $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                                ->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                                ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                                ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                                ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                                ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                                ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                                ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                                ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                                ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                                ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                                ->simplePaginate($request['offset']);
                            TransaccionBodegaResource::collection($results);
                            $results->appends(['offset' => $request['offset']]);
                    }
                } else {
                    Log::channel('testing')->info('Log', ['entro al else 127 del coordinador', $estado]);
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->where('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                        ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        ->simplePaginate($request['offset']);
                    Log::channel('testing')->info('Log', ['Datos encontrado: ', $results]);
                    TransaccionBodegaResource::collection($results);
                    $results->appends(['offset' => $request['offset']]);
                }
            } elseif (auth()->user()->hasRole(User::ROL_BODEGA)) {
                Log::channel('testing')->info('Log', ['entro al TODO del bodeguero', $estado]);
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', $estado)
                    ->simplePaginate($request['offset']);
                TransaccionBodegaResource::collection($results);
                $results->appends(['offset' => $request['offset']]);
            } else {
                Log::channel('testing')->info('Log', ['entro al caso contrario de la 109', $estado]);
                switch ($estado) {
                    case 'TODO':
                        Log::channel('testing')->info('Log', ['entro al TODO de la 142', $estado]);
                        $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                            ->where('solicitante_id', auth()->user()->empleado->id)
                            ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                            ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                            ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                            ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                        break;
                    case 'ESPERA':
                        Log::channel('testing')->info('Log', ['entro al ESPERA', $estado]);
                        $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                            ->where('solicitante_id', auth()->user()->empleado->id)
                            ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                            ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                            ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                            ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                            ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                            ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                            ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                            ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                        break;
                    case 'PENDIENTE':
                        Log::channel('testing')->info('Log', ['entro al PENDIENTE', $estado]);
                        $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                            ->where('solicitante_id', auth()->user()->empleado->id)
                            ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                            ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                            ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                            ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                            ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                            ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                            ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                            ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                        break;
                    default:
                        Log::channel('testing')->info('Log', ['entro al DEFAULT', $estado]);
                        $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                            ->where('solicitante_id', auth()->user()->empleado->id)
                            ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                            ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                            ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                            ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                            ->where('estados_transacciones_bodega.nombre', $estado)
                            ->simplePaginate($request['offset']);
                        TransaccionBodegaResource::collection($results);
                        $results->appends(['offset' => $request['offset']]);
                }
                /* if ($estado === 'TODO') {
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->where('solicitante_id', auth()->user()->empleado->id)
                        ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        ->simplePaginate($request['offset']);
                    TransaccionBodegaResource::collection($results);
                    $results->appends(['offset' => $request['offset']]);
                } else {
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->where('solicitante_id', auth()->user()->empleado->id)
                        ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                        ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                        ->where('estados_transacciones_bodega.nombre', $estado)
                        ->simplePaginate($request['offset']);
                    TransaccionBodegaResource::collection($results);
                    $results->appends(['offset' => $request['offset']]);
                } */
            }
        } else {
            if (auth()->user()->hasRole(User::ROL_COORDINADOR)) {
                Log::channel('testing')->info('Log', ['entro al coordinador del else de la linea 225', $estado]);
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')->filter()->get();
            }
            if (auth()->user()->hasRole(User::ROL_BODEGA)) {
                if ($estado) {
                    Log::channel('testing')->info('Log', ['entro al bodeguero del 235', $estado]);
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                        ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                        ->where('estados_transacciones_bodega.nombre', $estado)
                        ->ignoreRequest(['estado'])->filter()->get();
                    $results = TransaccionBodegaResource::collection($results);
                } else {
                    Log::channel('testing')->info('Log', ['entro al else de la 246', $estado]);
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        ->ignoreRequest(['estado'])->filter()->get();
                    $results = TransaccionBodegaResource::collection($results);
                }
            } else {
                Log::channel('testing')->info('Log', ['entro al ELSe chiquito', $estado]);
                $transacciones = TransaccionBodega::ignoreRequest(['estado'])->filter()->orWhere('solicitante_id', auth()->user()->empleado->id)->get();
                $transacciones = $transacciones->filter(fn ($transaccion) => $transaccion->subtipo->tipoTransaccion->tipo === 'EGRESO');
                Log::channel('testing')->info('Log', ['entro al casi al final', $estado]);
                return  TransaccionBodegaResource::collection(TransaccionBodega::filtrarTransacciones($transacciones, request('estado')));
            }
        }
        Log::channel('testing')->info('Log', ['LLego al final ', $estado]);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TransaccionBodegaRequest $request)
    {
        Log::channel('testing')->info('Log', ['Datos recibidos', $request->all()]);
        try {
            $datos = $request->validated();
            Log::channel('testing')->info('Log', ['Datos validados', $datos]);
            DB::beginTransaction();
            $datos['subtipo_id'] = $request->safe()->only(['subtipo'])['subtipo'];
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            if ($request->subtarea) {
                $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
            }
            if ($request->per_atiende) {
                $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
            }
            //datos de las relaciones muchos a muchos
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

            Log::channel('testing')->info('Log', ['Datos validados', $datos]);

            //Creacion de la transaccion
            $transaccion = TransaccionBodega::create($datos);


            //Guardar la autorizacion con su observacion
            if ($request->observacion_aut) {
                $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['observacion_aut']]);
            } else {
                $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
            }

            //Guardar el estado con su observacion
            if ($request->observacion_est) {
                $transaccion->estados()->attach($datos['estado_id'], ['observacion' => $datos['observacion_est']]);
            } else {
                $transaccion->estados()->attach($datos['estado_id']);
            }
            //Guardar los productos seleccionados
            foreach ($request->listadoProductosSeleccionados as $listado) {
                $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
            }

            DB::commit(); //Se registra la transaccion y sus detalles exitosamente

            $modelo = new TransaccionBodegaResource($transaccion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de ingreso', $e->getMessage()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TransaccionBodega $transaccion)
    {
        // Log::channel('testing')->info('Log', ['Transaccion en el show de ingreso', $transaccion]);
        $modelo = new TransaccionBodegaResource($transaccion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TransaccionBodegaRequest $request, TransaccionBodega $transaccion)
    {
        $datos = $request->validated();
        $datos['subtipo_id'] = $request->safe()->only(['subtipo'])['subtipo'];
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        if ($request->subtarea_id) {
            $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
        }
        if ($request->per_atiende) {
            $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
        }
        //datos de las relaciones muchos a muchos
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

        if ($transaccion->solicitante->id === auth()->user()->empleado->id) {
            try {
                DB::beginTransaction();
                $transaccion->update($datos); //Actualizacion de la transacción
                $transaccion->detalles()->detach(); //Borra los registros de la tabla intermedia para guardar los modificados
                foreach ($request->listadoProductosSeleccionados as $listado) { //Guarda los productos seleccionados
                    $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
                }

                DB::commit();

                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
            }

            return response()->json(compact('mensaje', 'modelo'));
        } else {
            //Aquí el coordinador o jefe inmediato autoriza la transaccion de sus subordinados y modifica los datos del listado
            if ($transaccion->per_autoriza_id === auth()->user()->empleado->id) {
                try {
                    DB::beginTransaction();
                    if ($request->observacion_aut) {
                        $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['observacion_aut']]);
                    } else {
                        $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
                    }
                    $transaccion->detalles()->detach();
                    foreach ($request->listadoProductosSeleccionados as $listado) { //Guarda los productos seleccionados
                        $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
                    }
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar la autorización' . $e->getMessage()], 422);
                }

                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje =  'Autorización actualizada correctamente';
                return response()->json(compact('mensaje', 'modelo'));
            } else {
                if (auth()->user()->hasRole(User::ROL_BODEGA)) {
                    try {
                        DB::beginTransaction();
                        if ($request->observacion_est) {
                            $transaccion->estados()->attach($datos['estado'], ['observacion' => $datos['observacion_est']]);
                        } else {
                            $transaccion->estados()->attach($datos['estado_id']);
                        }
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro'], 422);
                    }
                }

                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje = 'Estado actualizado correctamente';
                return response()->json(compact('mensaje', 'modelo'));
            }
        }

        /* $message = 'No tienes autorización para modificar esta solicitud';
        $errors = ['message' => $message];
        return response()->json(['errors' => $errors], 422); */
    }

    /**
     * Eliminar
     */
    public function destroy(TransaccionBodega $transaccion)
    {
        $transaccion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
