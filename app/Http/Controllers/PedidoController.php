<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoRequest;
use App\Http\Resources\PedidoResource;
use App\Models\Pedido;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Src\Config\RutasStorage;
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

        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            // Log::channel('testing')->info('Log', ['Es bodeguero:', $estado]);
            $results = Pedido::filtrarPedidosBodeguero($estado);
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
        Log::channel('testing')->info('Log', ['Request recibida en pedido:', $request->all()]);
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
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
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

        // Respuesta
        $pedido->update($datos);
        $modelo = new PedidoResource($pedido->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
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
    public function imprimir(Pedido $pedido){
        $resource = new PedidoResource($pedido);
        // Log::channel('testing')->info('Log', ['pedido es', $pedido]);
        // Log::channel('testing')->info('Log', ['pedido es', $resource]);
        // Log::channel('testing')->info('Log', ['pedido es', $modelo]);
        // $modelo = ['pedido'=>json_decode($resource->toJson(), true)];
        // $dompdf= new Dompdf();
        // $dompdf->setPaper('A4', 'landscape');
        // $dompdf->loadHtmlFile('pedidos.pedido', $resource->resolve());
        // $dompdf->render();
        // $dompdf->stream();

        try{
        // json_decode((new PedidoResource($pedido)))->toArray()
        $pdf = Pdf::loadView('pedidos.pedido', $resource->resolve());
        $pdf->setPaper('A5', 'landscape');
        $pdf->render();
        $file =$pdf->output();
        // $filename = storage_path('public\\pedidos\\').'Pedido_'.$resource->id.'_'.time().'.pdf';
        $filename = "pedidos/pedido_".$resource->id."_".time().".pdf";
        Log::channel('testing')->info('Log', ['NOMBRE DE ARCHIVO', $filename]);
        $bytes = file_put_contents($filename, $file);
        // Log::channel('testing')->info('Log', ['BYTES', $bytes]);
        // file_put_contents($filename, $file);
    //     $headers = [
    //         'Content-Type'=> 'application/pdf',
    //         'charset'=>'UTF-8',
    // ];
    // $archivo = readfile($filename);
        // return response()->download($filename, 'pedido.pdf', ['Content-Type'=>'application/pdf',]);
        return response()->download($filename);
        // return $pdf->download('pedido_'.$resource->id.'_'.time().'.pdf');
        }catch(Exception $e){
            Log::channel('testing')->info('Log', ['Error al generar el pdf', $e->getLine(), $e->getMessage()]);
            return response()->json('Error:'.$e->getLine());
        }
    }

    public function mostrar(Pedido $pedido){
        $resource = new PedidoResource($pedido);

        return view('pedidos.pedido', [$resource->resolve(),'usuario'=>auth()->user()->empleado]);
    }


    //retorna un qr
    public function qrview(){
        return view('qrcode');
    }
}
