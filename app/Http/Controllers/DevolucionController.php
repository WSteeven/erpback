<?php

namespace App\Http\Controllers;

use App\Events\DevolucionActualizadaSolicitanteEvent;
use App\Events\DevolucionAutorizadaEvent;
use App\Events\DevolucionCreadaEvent;
use App\Http\Requests\DevolucionRequest;
use App\Http\Resources\DevolucionResource;
use App\Models\Autorizacion;
use App\Models\Devolucion;
use App\Models\EstadoTransaccion;
use App\Models\Producto;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\TryCatch;
use Src\Shared\Utils;

class DevolucionController extends Controller
{
    private $entidad = 'Devolución';
    public function __construct()
    {
        $this->middleware('can:puede.ver.devoluciones')->only('index', 'show');
        $this->middleware('can:puede.crear.devoluciones')->only('store');
        $this->middleware('can:puede.editar.devoluciones')->only('update');
        $this->middleware('can:puede.eliminar.devoluciones')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $offset = $request['offset'];
        $estado = $request['estado'];
        $campos = explode(',', $request['page']);
        $results = [];

        switch ($request->estado) {
            case 'PENDIENTE':
                if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE])) {
                    $results = Devolucion::where('autorizacion_id', 1)->where('estado', Devolucion::CREADA)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = Devolucion::where('autorizacion_id', 1)->where('estado', Devolucion::CREADA)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            case 'APROBADO':
                if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE])) {
                    $results = Devolucion::where('autorizacion_id', 2)->where('estado', Devolucion::CREADA)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = Devolucion::where('autorizacion_id', 2)->where('estado', Devolucion::CREADA)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            case 'CANCELADO':
                if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE])) {
                    $results = Devolucion::where('autorizacion_id', 3)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = Devolucion::where('autorizacion_id', 3)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            case 'ANULADA':
                if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE])) {
                    $results = Devolucion::where('estado', Devolucion::ANULADA)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = Devolucion::where('estado', Devolucion::ANULADA)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            default:
                $results = Devolucion::where('solicitante_id', auth()->user()->empleado->id)->orWhere('per_autoriza_id', auth()->user()->empleado->id)->orderBy('updated_at', 'desc')->get();
        }

        $results = DevolucionResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DevolucionRequest $request)
    {
        Log::channel('testing')->info('Log', ['recibido en el store de devoluciones', $request->all()]);
        $url = '/devoluciones';
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
            $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['stock_personal'] = $request->es_para_stock;

            // Respuesta
            $devolucion = Devolucion::create($datos);
            Log::channel('testing')->info('Log', ['devolucion creada', $devolucion]);
            $modelo = new DevolucionResource($devolucion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            foreach ($request->listadoProductos as $listado) {
                $devolucion->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad']]);
            }


            DB::commit();
            $msg = 'Devolución N°' . $devolucion->id . ' ' . $devolucion->solicitante->nombres . ' ' . $devolucion->solicitante->apellidos . ' ha realizado una devolución desde el lugar ' . $devolucion->canton->canton . ' . La autorización está ' . $devolucion->autorizacion->nombre;
            event(new DevolucionCreadaEvent($msg, $url, $devolucion, $devolucion->solicitante_id, $devolucion->per_autoriza_id, false));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Devolucion $devolucion)
    {
        $modelo = new DevolucionResource($devolucion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(DevolucionRequest $request, Devolucion $devolucion)
    {
        $url = '/devoluciones';
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];

        // Respuesta
        $devolucion->update($datos);
        $modelo = new DevolucionResource($devolucion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        $msg = $devolucion->autoriza->nombres . ' ' . $devolucion->autoriza->apellidos . ' ha actualizado tu devolución, el estado de Autorización es: ' . $devolucion->autorizacion->nombre;
        event(new DevolucionActualizadaSolicitanteEvent($msg, $url, $devolucion, $devolucion->per_autoriza_id, $devolucion->solicitante_id, true)); //Se usa para notificar al tecnico que se actualizó la devolucion

        if ($devolucion->autorizacion->nombre === Autorizacion::APROBADO) {
            $devolucion->latestNotificacion()->update(['leida' => true]);
            $msg = 'Hay una devolución recién autorizada en la ciudad ' . $devolucion->canton->canton . ' pendiente de despacho';
            event(new DevolucionAutorizadaEvent($msg, User::ROL_BODEGA, $url, $devolucion, true));
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Devolucion $devolucion)
    {
        $devolucion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    /**
     * Consultar datos sin el método show.
     */
    public function showPreview(Devolucion $devolucion)
    {
        $modelo = new DevolucionResource($devolucion);

        return response()->json(compact('modelo'), 200);
    }

    /**
     * Anular una devolución
     */
    public function anular(Request $request, Devolucion $devolucion)
    {
        $request->validate(['motivo' => ['required', 'string']]);
        $devolucion->causa_anulacion = $request['motivo'];
        $devolucion->estado = Devolucion::ANULADA;
        $devolucion->estado_bodega = EstadoTransaccion::ANULADA;
        $devolucion->save();

        $modelo = new DevolucionResource($devolucion->refresh());

        return response()->json(compact('modelo'));
    }

    public function imprimir(Devolucion $devolucion)
    {
        $resource = new DevolucionResource($devolucion);
        try {
            $pdf = Pdf::loadView('devoluciones.devolucion', $resource->resolve());
            $pdf->setPaper('A5', 'landscape');
            $pdf->render();
            $file = $pdf->output();

            return $file;

            //usar esto en caso de querer guardar los pdfs generados en el servidor backend

            // $filename = "pedido_".$resource->id."_".time().".pdf";
            // $ruta = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'devoluciones'.DIRECTORY_SEPARATOR.$filename;
            // file_put_contents($ruta, $file); en caso de que se quiera guardar el documento en el backend
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
        }
    }
}
