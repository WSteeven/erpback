<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetalleProductoRequest;
use App\Http\Resources\DetalleProductoResource;
use App\Models\DetalleProducto;
use App\Models\DetallesProducto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class DetalleProductoController extends Controller
{
    private $entidad = 'Detalle de producto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.detalles')->only('index', 'show');
        $this->middleware('can:puede.crear.detalles')->only('store');
        $this->middleware('can:puede.editar.detalles')->only('update');
        $this->middleware('can:puede.eliminar.detalles')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $search = $request['search'];
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $results = [];
        if ($request['campos']) {
            $results = DetalleProducto::ignoreRequest(['campos', 'search'])->filter()->get($campos);
            // $results = DetalleProductoResource::collection($results);
            // return response()->json(compact('results'));
        } else if ($page) {
            $results = DetalleProducto::simplePaginate($request['offset']);
            // DetalleProductoResource::collection($results);
            // $results->appends(['offset' => $request['offset']]);
        } else if ($search) {
            $results = DetalleProducto::search($search)->get();
        } else {
            $results = DetalleProducto::ignoreRequest(['search'])->filter()->get();
            // $results = DetalleProductoResource::collection($results);
        }
        $results = DetalleProductoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DetalleProductoRequest $request)
    {
        Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        try {
            $datos = $request->validated();
            Log::channel('testing')->info('Log', ['Datos validados:', $datos]);
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos['producto_id'] = $request->safe()->only(['producto'])['producto'];
            $datos['modelo_id'] = $request->safe()->only(['modelo'])['modelo'];
            // $datos['span_id'] = $request->safe()->only(['span'])['span'];
            // $datos['tipo_fibra_id'] = $request->safe()->only(['tipo_fibra'])['tipo_fibra'];
            // $datos['hilo_id'] = $request->safe()->only(['hilos'])['hilos'];
            // Log::channel('testing')->info('Log', ['Datos adaptados:', $datos]);
            //Respuesta
            $detalle = DetalleProducto::create($datos);
            // $modelo = DetalleProducto::create($datos);
            if ($request->categoria === 'INFORMATICA') {
                $detalle->computadora()->create([
                    // 'detalle_id'=>$datos['detalle_id'],
                    'memoria_id' => $datos['ram'],
                    'disco_id' => $datos['disco'],
                    'procesador_id' => $datos['procesador'],
                    'imei' => $datos['imei'],
                ]);
                DB::commit();
            }
            if ($request->es_fibra) {
                $detalle->fibra()->create([
                    // 'detalle_id'=>$datos['detalle_id'],
                    'span_id' => $datos['span'],
                    'tipo_fibra_id' => $datos['tipo_fibra'],
                    'hilo_id' => $datos['hilos'],
                    'punta_inicial' => $datos['punta_inicial'],
                    'punta_final' => $datos['punta_final'],
                    'custodia' => $datos['custodia'],
                ]);
                DB::commit();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e->getMessage()], 422);
        }
        $modelo = new DetalleProductoResource($detalle);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(DetalleProducto $detalle)
    {
        $modelo = new DetalleProductoResource($detalle);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(DetalleProductoRequest $request, DetalleProducto $detalle)
    {
        // Log::channel('testing')->info('Log', ['request recibida:', $request->all()]);
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            //Adaptacion de foreign keys
            $datos['producto_id'] = $request->safe()->only(['producto'])['producto'];
            $datos['modelo_id'] = $request->safe()->only(['modelo'])['modelo'];
            // $datos['span_id'] = $request->safe()->only(['span'])['span'];
            // $datos['tipo_fibra_id'] = $request->safe()->only(['tipo_fibra'])['tipo_fibra'];
            // $datos['hilo_id'] = $request->safe()->only(['hilos'])['hilos'];
            // Log::channel('testing')->info('Log', ['datos validados:', $datos]);

            //Respuesta
            $detalle->update($datos);
            if ($request->categoria === 'INFORMATICA') {
                $detalle->computadora()->update([
                    'memoria_id' => $datos['ram'],
                    'disco_id' => $datos['disco'],
                    'procesador_id' => $datos['procesador'],
                    'imei' => $datos['imei'],
                ]);
                DB::commit();
            }
            if ($request->es_fibra) {
                $detalle->fibra()->update([
                    'span_id' => $datos['span'],
                    'tipo_fibra_id' => $datos['tipo_fibra'],
                    'hilo_id' => $datos['hilos'],
                    'punta_inicial' => $datos['punta_inicial'],
                    'punta_final' => $datos['punta_final'],
                    'custodia' => $datos['custodia'],
                ]);
                DB::commit();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro', "excepción" => $e->getMessage()], 422);
        }
        $modelo = new DetalleProductoResource($detalle->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(DetalleProducto $detalle)
    {
        $detalle->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
