<?php

namespace App\Http\Controllers;

use App\Events\PedidoEvent;
use App\Http\Requests\PedidoRequest;
use App\Http\Resources\PedidoResource;
use App\Models\Autorizacion;
use App\Models\Pedido;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Src\Shared\Utils;

class PedidoController extends Controller
{
    private $entidad = 'Pedido';
    public function __construct()
    {
        $this->middleware('can:puede.ver.pedidos')->only('index', 'show');
        $this->middleware('can:puede.crear.pedidos')->only('store');
        $this->middleware('can:puede.editar.pedidos')->only('update');
        $this->middleware('can:puede.eliminar.pedidos')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $estado = $request['estado'];
        $results = [];

        if (auth()->user()->hasRole(User::ROL_BODEGA) && !auth()->user()->hasRole(User::ROL_ACTIVOS_FIJOS)) { //para que unicamente el bodeguero pueda ver las transacciones pendientes
            // Log::channel('testing')->info('Log', ['Es bodeguero:', $estado]);
            $results = Pedido::filtrarPedidosBodeguero($estado);
        } else if (auth()->user()->hasRole(User::ROL_ACTIVOS_FIJOS)) {
            $results = Pedido::filtrarPedidosActivosFijos($estado);
        } else {
            // Log::channel('testing')->info('Log', ['Es empleado:', $estado]);
            $results = Pedido::filtrarPedidosEmpleado($estado);
        }

        // Log::channel('testing')->info('Log', ['Resultados:', $estado, $results]);
        $results = PedidoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(PedidoRequest $request)
    {
        $url_pedido = '/pedidos';
        Log::channel('testing')->info('Log', ['Request recibida en pedido:', $request->all()]);
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

            // Respuesta
            $pedido = Pedido::create($datos);
            $modelo = new PedidoResource($pedido);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            foreach ($request->listadoProductos as $listado) {
                $pedido->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad']]);
            }
            DB::commit();

            /* Sending a notification to the user who autorized the order. */
            //logica para los eventos de las notificaciones
            if($pedido->solicitante_id==$pedido->per_autoriza_id && $pedido->autorizacion->nombre===Autorizacion::APROBADO){
                //No se hace nada y se crea la logica
                $msg = 'Pedido N°'.$pedido->id.' '.$pedido->solicitante->nombres.' '.$pedido->solicitante->apellidos. ' ha realizado un pedido en la sucursal '.$pedido->sucursal->lugar.' indicando que tú eres el responsable de los materiales, el estado del pedido es '.$pedido->autorizacion->nombre;
                event(new PedidoEvent($msg, $url_pedido, $pedido, $pedido->responsable_id));
            }else{
                $msg = 'Pedido N°'.$pedido->id.' '.$pedido->solicitante->nombres.' '.$pedido->solicitante->apellidos. ' ha realizado un pedido en la sucursal '.$pedido->sucursal->lugar.' y está '.$pedido->autorizacion->nombre.' de autorización';
                event(new PedidoEvent($msg,$url_pedido,  $pedido, $pedido->per_autoriza_id));
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }
    }

    /**
     * Consultar
     */
    public function show(Pedido $pedido)
    {
        $modelo = new PedidoResource($pedido);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(PedidoRequest $request, Pedido $pedido)
    {
        $url_pedido = '/pedidos';
        Log::channel('testing')->info('Log', ['entro en el update del pedido', ]);
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

            // Respuesta
            $pedido->update($datos);
            $modelo = new PedidoResource($pedido->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            //modifica los datos del listado, en caso de requerirse
            $pedido->detalles()->detach();
            foreach ($request->listadoProductos as $listado) {
                $pedido->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad']]);
            }
            DB::commit();

            if($pedido->autorizacion->nombre===Autorizacion::APROBADO){
                $msg = 'Tienes un pedido recién autorizado por atender en la sucursal '.$pedido->sucursal->lugar;
                event(new PedidoEvent($msg,$url_pedido, $pedido,  ))

            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro. '.$e->getMessage().' '.$e->getLine()], 422);
        }
    }

    /**
     * Eliminar
     */
    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    /**
     * Consultar datos sin el método show.
     */
    public function showPreview(Pedido $pedido)
    {
        $modelo = new PedidoResource($pedido);

        return response()->json(compact('modelo'), 200);
    }

    /**
     * Imprimir
     */
    public function imprimir(Pedido $pedido)
    {
        $resource = new PedidoResource($pedido);
        try {
            $pdf = Pdf::loadView('pedidos.pedido', $resource->resolve());
            $pdf->setPaper('A5', 'landscape');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            $file = $pdf->output(); //SE GENERA EL PDF
            $filename = "pedido_" . $resource->id . "_" . time() . ".pdf";

            $ruta = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'pedidos' . DIRECTORY_SEPARATOR . $filename;

            // $filename = storage_path('public\\pedidos\\').'Pedido_'.$resource->id.'_'.time().'.pdf';
            Log::channel('testing')->info('Log', ['El pedido es', $resource]);
            // file_put_contents($ruta, $file); en caso de que se quiera guardar el documento en el backend
            return $file;
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
        }
    }

    public function mostrar(Pedido $pedido)
    {
        $resource = new PedidoResource($pedido);

        return view('pedidos.pedido', [$resource->resolve(), 'usuario' => auth()->user()->empleado]);
    }


    //retorna un qr
    public function qrview()
    {
        return view('qrcode');
    }

    public function encabezado(){
        $pdf = Pdf::loadView('pedidos.encabezado_pie_numeracion');
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        // $pdf->output();
        // $pdf->stream();

        return $pdf->stream();
        // return view('pedidos.encabezado_pie_numeracion');
    }
    public function example(){
        $pdf = Pdf::loadView('pedidos.example');
        $pdf->render();
        return $pdf->stream();
    }
}
