<?php

namespace App\Http\Controllers;

use App\Events\PreingresoAutorizadoEvent;
use App\Events\PreingresoCreadoEvent;
use App\Http\Requests\PreingresoMaterialRequest;
use App\Http\Resources\PreingresoMaterialResource;
use App\Models\Pedido;
use App\Models\PreingresoMaterial;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class PreingresoMaterialController extends Controller
{
    private $entidad = 'Preingreso de Material';
    public function __construct()
    {
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
        if ($request->autorizacion_id) {
            switch ($request->autorizacion_id) {
                case 1: //PENDIENTE
                    $results = PreingresoMaterial::where('autorizacion_id', $request->autorizacion_id)
                        ->where(function ($query) {
                            $query->where('responsable_id', auth()->user()->empleado->id)
                                ->orWhere('autorizador_id', auth()->user()->empleado->id)
                                ->orWhere('coordinador_id', auth()->user()->empleado->id);
                        })->get();
                    break;
                case 2: //APROBADO
                    $results = PreingresoMaterial::where('autorizacion_id', $request->autorizacion_id)
                        ->where('responsable_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id)
                        ->orWhere('coordinador_id', auth()->user()->empleado->id)
                        ->get();
                    break;
                case 3: //CANCELADO
                    $results = PreingresoMaterial::where('autorizacion_id', $request->autorizacion_id)
                        ->where('responsable_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id)
                        ->orWhere('coordinador_id', auth()->user()->empleado->id)
                        ->get();
                    break;
                default:
                    $results = PreingresoMaterial::all();
            }
        } else {
            $results = PreingresoMaterial::all();
        }
        $results = PreingresoMaterialResource::collection($results);

        return response()->json(compact('results'));
    }
    /**
     * Guardar
     */
    public function store(PreingresoMaterialRequest $request)
    {
        // Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['autorizador_id'] = $request->safe()->only(['autorizador'])['autorizador'];
            $datos['responsable_id'] = $request->responsable_id;
            $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            if ($request->tarea) $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            Log::channel('testing')->info('Log', ['Datos validados y casteados:', $datos]);
            //Se crea el registro de preingreso
            $preingreso = PreingresoMaterial::create($datos);

            //Se crea los detalles y se almacena en detalles productos
            PreingresoMaterial::guardarDetalles($preingreso, $request->listadoProductos);

            //Se emite la notificación al autorizador
            if ($preingreso->tarea_id) event(new PreingresoCreadoEvent($preingreso, $preingreso->coordinador_id));
            else event(new PreingresoCreadoEvent($preingreso, $preingreso->autorizador_id));

            //Respuesta
            $modelo = new PreingresoMaterialResource($preingreso);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
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
        Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        $autorizacion_old = $preingreso->autorizacion_id;
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['autorizador_id'] = $request->safe()->only(['autorizador'])['autorizador'];
            $datos['responsable_id'] = $request->responsable_id;
            $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            if ($request->tarea) $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            Log::channel('testing')->info('Log', ['Datos validados y casteados:', $datos]);
            //Se actualiza el registro de preingreso
            $preingreso->update($datos);
            $preingreso->refresh();

            //Se actualizan los detalles
            PreingresoMaterial::actualizarDetalles($preingreso, $request->listadoProductos);

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
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro', "excepción" => $e->getMessage() . '. ' . $e->getLine()], 422);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }
}
