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
use Src\App\Bodega\DetalleProductoService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

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
        $search = $request['search'];
        $sucursal = $request['sucursal_id'];
        $page = $request['page'];
        if ($request->campos) $campos = explode(',', $request['campos']);
        if ($request->tipo_busqueda) {
            switch ($request->tipo_busqueda) {
                case 'only_inventario':
                    $results = DetalleProducto::whereHas('inventarios')->get()->take(10000);
                    $results2 = DetalleProducto::whereHas('itemsPreingresos')->get()->take(5000);
                    $results = $results->merge($results2);
                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
                case 'only_sucursal':
                    //aqui se lista solo los detalles que estan en la bodega seleccionada
                    $ids_detalles = Inventario::where('sucursal_id', $sucursal)->get('detalle_id');
                    $results = DetalleProducto::whereIn('id', $ids_detalles)
                        ->when($request->search, function ($query) use ($request) {
                            $query->where('descripcion', 'LIKE', '%' . $request->search . '%');
                        })->where('activo', true)->orderBy('descripcion')->get();
                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
                case 'only_cliente_tarea':
                    $results = Cliente::find($request->cliente_id)->detalles->unique()->where('activo', true);
                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
                default: //todos
                    if ($request->categoria_id && !is_null($request->categoria_id[0])) {
                        $results = DetalleProducto::withWhereHas('producto', function ($query) use ($request) {
                            $query->whereIn('categoria_id', $request->categoria_id);
                        })->orderBy('descripcion')->groupBy('descripcion')->get();
                    } else $results = DetalleProducto::where('activo', true)->orderBy('descripcion')->groupBy('descripcion')->get();

                    $results = DetalleProductoResource::collection($results);
                    return response()->json(compact('results'));
            }
        }
        if (!empty($campos)) {
            $results = DetalleProducto::ignoreRequest(['campos', 'search'])->filter()->get($campos);
        } else if ($page) {
            $results = DetalleProducto::simplePaginate($request['offset']);
        } else if ($search) { //en este caso busca en todos los detalles
            $results = DetalleProducto::search($search)->get();
        } else if ($sucursal) {
            if ($request->cliente_id) $ids_detalles = Inventario::where('sucursal_id', $sucursal)->where('cliente_id', $request->cliente_id)->get('detalle_id');
            else {
                $ids_detalles = Inventario::where('sucursal_id', $sucursal)->get('detalle_id');
                $ids_detalles_en_inventario = Inventario::all('detalle_id');
            }
            $results = DetalleProducto::whereIn('id', $ids_detalles)->get();
            $r2 = DetalleProducto::whereNotIn('id', $ids_detalles_en_inventario)->get();
            $results = $results->concat($r2);
        } else {
            $results = DetalleProducto::ignoreRequest(['search'])->filter()->get();
        }
        $results = DetalleProductoResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     * @throws Throwable
     */
    public function store(DetalleProductoRequest $request)
    {
        $seriales = [];
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            //Abordamos el manejo de seriales estrategicamente
            // === DETERMINAR SERIALES ===
            if ($request->hasFile('archivo') && $request->subida_masiva) {
                // CASO 1: Subida masiva → leer del Excel (los seriales limpios que guardamos en la regla)
                $seriales = DetalleProductoService::obtenerSerialesExcel($request->file('archivo'));

                if (empty($seriales)) throw new Exception('No se encontraron seriales válidos en el archivo Excel.');
            } elseif ($request->filled('seriales') && is_array($request->seriales) && !empty($request->seriales)) {
                // CASO 2: Varios seriales manuales (formulario normal con array)
                foreach ($request->seriales as $item) {
                    if (!empty($item['serial']))
                        $seriales[] = trim($item['serial']);
                }
            } else {
                // CASO 3: Un solo serial (o ninguno → será nullable)
                $seriales[] = $request->input('serial');
            }


            // === CREAR REGISTROS ===
            $detallesCreados = [];
            foreach ($seriales as $serial) {
                $datos['serial'] = $serial;
                $detalle = DetalleProducto::crearDetalle($request, $datos);

                if ($detalle->esActivo) ActivoFijo::cargarComoActivo($detalle, Cliente::JPCONSTRUCRED);
                $detallesCreados[] = $detalle;
            }
            DB::commit();
            $modelo = DetalleProductoResource::collection($detallesCreados);
            $mensaje = count($detallesCreados) > 1 ? "Se han registrado " . count($detallesCreados) . " detalles correctamente." : Utils::obtenerMensaje($this->entidad, 'store');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['Error en store de DetalleProductoController:', $e->getMessage(), $e->getLine(), $e]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'Problema al insertar el registro');
//            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e->getMessage() . $e->getLine()], 422);
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
     * @throws Throwable
     */
    public function update(DetalleProductoRequest $request, DetalleProducto $detalle)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();

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
