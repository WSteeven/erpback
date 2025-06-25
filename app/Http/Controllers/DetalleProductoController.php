<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetalleProductoRequest;
use App\Http\Resources\DetalleProductoResource;
use App\Http\Resources\SucursalResource;
use App\Models\ActivosFijos\ActivoFijo;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\Inventario;
use App\Models\Sucursal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class DetalleProductoController extends Controller
{
    private string $entidad = 'Detalle de producto';

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
        $controller_method = $request->route()->getActionMethod();
//        Log::channel('testing')->info('Log', ['ruta:', $controller_method]);
//        Log::channel('testing')->info('Log', ['Metodo recibido:', $request->route()]);
        $search = $request['search'];
        $sucursal = $request['sucursal_id'];
        $page = $request['page'];
        if ($request->campos) $campos = explode(',', $request['campos']);
        $results = [];
        if ($request->tipo_busqueda) {
            switch ($request->tipo_busqueda) {
                case 'only_inventario':
                    $results = DetalleProducto::whereHas('inventarios')->get()->take(10000);
                    $results2 = DetalleProducto::whereHas('itemsPreingresos')->get()->take(5000);
                    $results = $results->merge($results2);
                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
                    break;
                case 'only_sucursal':
                    //aqui se lista solo los detalles que estan en la bodega seleccionada
                    $ids_detalles = Inventario::where('sucursal_id', $sucursal)->get('detalle_id');
                    $results = DetalleProducto::whereIn('id', $ids_detalles)
                        ->when($request->search, function ($query) use ($request) {
                            $query->where('descripcion', 'LIKE', '%' . $request->search . '%');
                        })->where('activo', true)->orderBy('descripcion', 'asc')->get();
                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
                    break;
                case 'only_cliente_tarea':
//                    Log::channel('testing')->info('Log', ['eSTOY EN EL CASE DE CLIENTE TAREA:', $request->all()]);
                    //aqui se lista solo los detalles que tienen stock en inventario con el cliente de la tarea
                    // $ids_detalles = Inventario::where('cliente_id', $request->cliente_id)->limit(990)->get('detalle_id');
                    // Log::channel('testing')->info('Log', ['los detalles como tal:', DetalleProducto::whereIn('id', $ids_detalles)->get()]);
                    $results = Cliente::find($request->cliente_id)->detalles->unique()->where('activo', true);
                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
                    break;
                default: //todos
                    if ($request->categoria_id && !is_null($request->categoria_id[0])) {
                        $results = DetalleProducto::withWhereHas('producto', function ($query) use ($request) {
                            $query->whereIn('categoria_id', $request->categoria_id);
                        })->orderBy('descripcion', 'asc')->groupBy('descripcion')->get();
                    } else $results = DetalleProducto::where('activo', true)->orderBy('descripcion', 'asc')->groupBy('descripcion')->get();

                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
            }
        }
        if (!empty($campos)) {
            $results = DetalleProducto::ignoreRequest(['campos', 'search'])->filter()->get($campos);
        } else if ($page) {
            $results = DetalleProducto::simplePaginate($request['offset']);
        } else if ($search) { //en este caso busca en todos los detalles
//            Log::channel('testing')->info('Log', ['Pasó por el if de search:', $request->all()]);
            $results = DetalleProducto::search($search)->get();
        } else if ($sucursal) {
//            Log::channel('testing')->info('Log', ['Pasó por el if de sucursal:', $request->all()]);
            // Log::channel('testing')->info('Log', ['Pasó por el if de search:']);
            $results = DetalleProducto::search($search)->get();
        } else if ($sucursal) {
            // Log::channel('testing')->info('Log', ['Pasó por el if de sucursal:', $request->all()]);
            if ($request->cliente_id) $ids_detalles = Inventario::where('sucursal_id', $sucursal)->where('cliente_id', $request->cliente_id)->get('detalle_id');
            else {
                $ids_detalles = Inventario::where('sucursal_id', $sucursal)->get('detalle_id');
                $ids_detalles_en_inventario = Inventario::all('detalle_id');
            }
            $results = DetalleProducto::whereIn('id', $ids_detalles)->get();
            $r2 = DetalleProducto::whereNotIn('id', $ids_detalles_en_inventario)->get();
            // Log::channel('testing')->info('Log', ['resultados filtrados:', $results->count(), $r2->count()]);
            $results = $results->concat($r2);
//            Log::channel('testing')->info('Log', ['resultados filtrados:', $results->count()]);
        } else {
            $results = DetalleProducto::ignoreRequest(['search'])->filter()->get();
        }
        $results = DetalleProductoResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(DetalleProductoRequest $request)
    {
        // Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        try {
            $datos = $request->validated();
            // Log::channel('testing')->info('Log', ['Datos validados:', $datos]);
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos['producto_id'] = $request->safe()->only(['producto'])['producto'];
            $datos['modelo_id'] = $request->safe()->only(['modelo'])['modelo'];
            if (count($request->seriales) > 0) {
                // Log::channel('testing')->info('Log', ['Hay:', count($request->seriales), 'numeros de serie']);
                foreach ($request->seriales as $item) {
//                    Log::channel('testing')->info('Log', ['Serial:', $item['serial']]);
                    //aqui se pondria la siguiente linea
                    $datos['serial'] = $item['serial'];
                    $detalle = DetalleProducto::crearDetalle($request, $datos);
                }
            } else {
                //Respuesta
                $detalle = DetalleProducto::crearDetalle($request, $datos);
            }

            if ($detalle) {
                if ($detalle->esActivo) ActivoFijo::cargarComoActivo($detalle, Cliente::JPCONSTRUCRED);
            }
            $modelo = new DetalleProductoResource($detalle);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e->getMessage() . $e->getLine()], 422);
        }

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
//        Log::channel('testing')->info('Log', ['request recibida:', $request->all()]);
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

            // Respuesta
            if ($datos['fotografia'] && Utils::esBase64($datos['fotografia'])) $datos['fotografia'] = (new GuardarImagenIndividual($datos['fotografia'], RutasStorage::FOTOGRAFIAS_DETALLE_PRODUCTO, $detalle->fotografia))->execute();
            else unset($datos['fotografia']);

            if ($datos['fotografia_detallada'] && Utils::esBase64($datos['fotografia_detallada'])) $datos['fotografia_detallada'] = (new GuardarImagenIndividual($datos['fotografia_detallada'], RutasStorage::FOTOGRAFIAS_DETALLE_PRODUCTO, $detalle->fotografia_detallada))->execute();
            else unset($datos['fotografia_detallada']);

            $detalle->update($datos);
            if ($detalle->esActivo) ActivoFijo::cargarComoActivo($detalle, Cliente::JPCONSTRUCRED);
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
     * Actualmente no se permite eliminar un detalle de producto para evitar caer en el error de asignarlo a alguien
     * y luego eliminarlo, ocasionando inconsistencias con la información.
     * @throws ValidationException
     */
    public function destroy()
    {
        throw ValidationException::withMessages(['error' => 'Por razones de seguridad, no se puede eliminar un detalle ya creado. Por favor, desactívalo o comunícate con el departamento de Informática para más información.']);
    }

    /**
     * Desactivar un detalle especifico
     */
    public function desactivar(DetalleProducto $detalle)
    {
        // Log::channel('testing')->info('Log', ['Inicio del metodo desactivar:', $detalle]);
        $detalle->activo = !$detalle->activo;
        $detalle->save();

        $modelo = new DetalleProductoResource($detalle->refresh());
        return response()->json(compact('modelo'));
    }


    public function obtenerMateriales(Request $request)
    {
        $detalles = DetalleProducto::whereHas('producto', function ($query) {
            $query->whereIn('categoria_id', [7, 4, 1]);
        })->where(function ($query) use ($request) {
            $query->where('descripcion', 'LIKE', '%' . $request->search . '%');
            $query->orWhere('serial', 'LIKE', '%' . $request->search . '%');
        })->groupBy('descripcion')->get();

        $results = DetalleProductoResource::collection($detalles);
        return response()->json(compact('results'));
    }

    public function sucursalesDetalle(Request $request)
    {
        $ids_sucursales = Inventario::where('detalle_id', $request->detalle_id)->get('sucursal_id');
        // $ids_sucursales = DetalleProducto::whereHas('inventarios', function ($query) use ($request) {
        //     $query->where('detalle_id', $request->detalle_id);
        // })->get();
        $results = SucursalResource::collection(Sucursal::whereIn('id', $ids_sucursales)->get());
        return response()->json(compact('results'));
    }
}
