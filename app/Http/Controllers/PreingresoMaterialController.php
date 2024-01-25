<?php

namespace App\Http\Controllers;

use App\Events\PreingresoAutorizadoEvent;
use App\Events\PreingresoCreadoEvent;
use App\Http\Requests\PreingresoMaterialRequest;
use App\Http\Resources\PreingresoMaterialResource;
use App\Models\Pedido;
use App\Models\PreingresoMaterial;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\App\Bodega\PreingresoMaterialService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class PreingresoMaterialController extends Controller
{
    private $entidad = 'Preingreso de Material';
    private $archivoService;
    private $servicio;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->servicio = new PreingresoMaterialService();
        $this->middleware('can:puede.ver.preingresos_materiales')->only('index', 'show');
        $this->middleware('can:puede.crear.preingresos_materiales')->only('store');
        $this->middleware('can:puede.editar.preingresos_materiales')->only('update');
        $this->middleware('can:puede.eliminar.preingresos_materiales')->only('destroy');
    }


    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = $this->servicio->filtrarPreingresos($request);
        $results = PreingresoMaterialResource::collection($results);

        return response()->json(compact('results'));
    }
    /**
     * Guardar
     */
    public function store(PreingresoMaterialRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['autorizador_id'] = $request->safe()->only(['autorizador'])['autorizador'];
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];
            $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            if ($request->proyecto) $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
            if ($request->etapa) $datos['etapa_id'] = $request->safe()->only(['etapa'])['etapa'];
            if ($request->tarea) $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            //Se crea el registro de preingreso
            $preingreso = PreingresoMaterial::create($datos);

            //Se crea los detalles y se almacena en detalles productos
            $this->servicio->guardarDetalles($preingreso, $request->listadoProductos);
            // PreingresoMaterial::guardarDetalles($preingreso, $request->listadoProductos);
            //Se emite la notificación al autorizador
            if ($preingreso->tarea_id) event(new PreingresoCreadoEvent($preingreso, $preingreso->coordinador_id));
            else event(new PreingresoCreadoEvent($preingreso, $preingreso->autorizador_id));
            //Respuesta
            $modelo = new PreingresoMaterialResource($preingreso);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Erorr: ', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage() . '. ' . $e->getLine()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e->getMessage() . '. ' . $e->getLine()], 422);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(PreingresoMaterial $preingreso)
    {
        $modelo = new PreingresoMaterialResource($preingreso);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(PreingresoMaterialRequest $request, PreingresoMaterial $preingreso)
    {
        $autorizacion_old = $preingreso->autorizacion_id;
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['autorizador_id'] = $request->safe()->only(['autorizador'])['autorizador'];
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];
            $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            if ($request->proyecto) $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
            if ($request->etapa) $datos['etapa_id'] = $request->safe()->only(['etapa'])['etapa'];
            if ($request->tarea) $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            //Se actualiza el registro de preingreso
            $preingreso->update($datos);
            $preingreso->refresh();

            //Se actualizan los detalles
            $this->servicio->actualizarDetalles($preingreso, $request->listadoProductos);

            //Se emite la notificación al responsable indicando que se aprobo o no su preingreso
            if ($preingreso->autorizacion_id !== $autorizacion_old) {
                event(new PreingresoAutorizadoEvent($preingreso));
            }

            //Respuesta
            $modelo = new PreingresoMaterialResource($preingreso->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al actualizar el registro' => [$e->getMessage() . '. ' . $e->getLine()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro', "excepción" => $e->getMessage() . '. ' . $e->getLine()], 422);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Imprimir
     */
    public function imprimir(PreingresoMaterial $preingreso){
        $configuracion = ConfiguracionGeneral::first();
        $resource = new PreingresoMaterialResource($preingreso);
        $persona_responsable = Empleado::find($preingreso->responsable_id);
        $persona_autoriza = Empleado::find($preingreso->autorizador_id);
        try {
            $preingreso = $resource->resolve();
            $preingreso['listadoProductos'] = PreingresoMaterial::listadoProductos($preingreso['id']);
            Log::channel('testing')->info('Log', ['Preingreso a imprimir es: ', $preingreso]);
            $pdf = Pdf::loadView('bodega.preingresos.preingreso', compact(['preingreso', 'configuracion', 'persona_responsable', 'persona_autoriza']));
            $pdf->setPaper('A5', 'landscape');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            $file = $pdf->output(); //SE GENERA EL PDF

            // return $pdf->stream();
            return $file;
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, PreingresoMaterial $preingreso)
    {
        try {
            $results = $this->archivoService->listarArchivos($preingreso);
        } catch (\Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, PreingresoMaterial $preingreso)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($preingreso, $request->file, RutasStorage::PREINGRESOS->value . '_' . $preingreso->id);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            return $ex;
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }
}
