<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetalleProductoRequest;
use App\Http\Resources\DetalleProductoResource;
use App\Models\Cliente;
use App\Models\ComputadoraTelefono;
use App\Models\DetalleProducto;
use App\Models\DetallesProducto;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\Inventario;
use Exception;
use GuzzleHttp\Client;
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
        Log::channel('testing')->info('Log', ['Inicio del metodo, se recibe lo siguiente:', $request->all()]);
        $search = $request['search'];
        $sucursal = $request['sucursal_id'];
        $page = $request['page'];
        if ($request->campos) $campos = explode(',', $request['campos']);
        $results = [];
        if ($request->tipo_busqueda) {
            switch ($request->tipo_busqueda) {
                case 'only_sucursal':
                    //aqui se lista solo los detalles que estan en la bodega seleccionada
                    $ids_detalles = Inventario::where('sucursal_id', $sucursal)->get('detalle_id');
                    $results = DetalleProducto::whereIn('id', $ids_detalles)->orderBy('descripcion', 'asc')->get();
                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
                    break;
                case 'only_cliente_tarea':
                    Log::channel('testing')->info('Log', ['eSTOY EN EL CASE DE CLIENTE TAREA:', $request->all()]);
                    //aqui se lista solo los detalles que tienen stock en inventario con el cliente de la tarea
                    // $ids_detalles = Inventario::where('cliente_id', $request->cliente_id)->limit(990)->get('detalle_id');
                    // Log::channel('testing')->info('Log', ['los detalles CLIENTE TAREA:', $ids_detalles->count()]);
                    // Log::channel('testing')->info('Log', ['los detalles como tal:', DetalleProducto::whereIn('id', $ids_detalles)->get()]);
                    $results = Cliente::find($request->cliente_id)->detalles->unique();
                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
                    break;
                default: //todos
                    $results = DetalleProducto::orderBy('descripcion', 'asc')->groupBy('descripcion')->get();
                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
            }
        }
        if (!empty($campos)) {
            Log::channel('testing')->info('Log', ['Que tiene campos:', $campos]);
            Log::channel('testing')->info('Log', ['Pasó por el if de campos:']);
            $results = DetalleProducto::ignoreRequest(['campos', 'search'])->filter()->get($campos);
            // return response()->json(compact('results'));
        } else if ($page) {
            Log::channel('testing')->info('Log', ['Pasó por el if de page:']);
            $results = DetalleProducto::simplePaginate($request['offset']);
        } else if ($search) { //en este caso busca en todos los detalles
            Log::channel('testing')->info('Log', ['Pasó por el if de search:']);
            $results = DetalleProducto::search($search)->get();
        } else if ($sucursal) {
            Log::channel('testing')->info('Log', ['Pasó por el if de sucursal:', $request->all()]);
            if ($request->cliente_id) $ids_detalles = Inventario::where('sucursal_id', $sucursal)->where('cliente_id', $request->cliente_id)->get('detalle_id');
            else {
                $ids_detalles = Inventario::where('sucursal_id', $sucursal)->get('detalle_id');
                $ids_detalles_en_inventario = Inventario::all('detalle_id');
            }
            $results = DetalleProducto::whereIn('id', $ids_detalles)->get();
            $r2 = DetalleProducto::whereNotIn('id', $ids_detalles_en_inventario)->get();
            Log::channel('testing')->info('Log', ['resultados filtrados:', $results->count(), $r2->count()]);
            $results = $results->concat($r2);
            Log::channel('testing')->info('Log', ['resultados filtrados:', $results->count()]);
        } else {
            Log::channel('testing')->info('Log', ['Pasó por el else general:']);
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
            if (count($request->seriales) > 0) {
                Log::channel('testing')->info('Log', ['Hay:', count($request->seriales), 'numeros de serie']);
                foreach ($request->seriales as $item) {
                    Log::channel('testing')->info('Log', ['Serial:', $item['serial']]);
                    //aqui se pondria la siguiente linea
                    $datos['serial'] = $item['serial'];
                    $detalle = DetalleProducto::crearDetalle($request, $datos);
                }
            } else {
                //Respuesta
                $detalle = DetalleProducto::crearDetalle($request, $datos);
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
        Log::channel('testing')->info('Log', ['request recibida:', $request->all()]);
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
                if ($detalle->computadora()->first()) {
                    $detalle->computadora()->update([
                        'memoria_id' => $datos['ram'],
                        'disco_id' => $datos['disco'],
                        'procesador_id' => $datos['procesador'],
                        'imei' => $datos['imei'],
                    ]);
                } else {
                    $detalle->computadora()->create([
                        'memoria_id' => $datos['ram'],
                        'disco_id' => $datos['disco'],
                        'procesador_id' => $datos['procesador'],
                        'imei' => $datos['imei'],
                    ]);
                }
                DB::commit();
            }
            if ($request->es_fibra) {
                if ($detalle->fibra()->first()) {
                    $detalle->fibra()->update([
                        'span_id' => $datos['span'],
                        'tipo_fibra_id' => $datos['tipo_fibra'],
                        'hilo_id' => $datos['hilos'],
                        'punta_inicial' => $datos['punta_inicial'],
                        'punta_final' => $datos['punta_final'],
                        'custodia' => $datos['custodia'],
                    ]);
                } else {
                    $detalle->fibra()->create([
                        'span_id' => $datos['span'],
                        'tipo_fibra_id' => $datos['tipo_fibra'],
                        'hilo_id' => $datos['hilos'],
                        'punta_inicial' => $datos['punta_inicial'],
                        'punta_final' => $datos['punta_final'],
                        'custodia' => $datos['custodia'],
                    ]);
                }
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
