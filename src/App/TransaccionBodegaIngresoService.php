<?php

namespace Src\App;

use App\Models\DetalleDevolucionProducto;
use App\Models\Devolucion;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Motivo;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Src\App\Bodega\DevolucionService;
use Src\App\Sistema\PaginationService;
use Src\Config\ClientesCorporativos;
use Src\Config\Constantes;
use Throwable;

class TransaccionBodegaIngresoService
{


    public static function filtrarIngresoPorTipoFiltro($request)
    {
        $tipo_transaccion = TipoTransaccion::where('nombre', TipoTransaccion::INGRESO)->first();
        $motivos = Motivo::where('tipo_transaccion_id', $tipo_transaccion->id)->get('id');
        switch ($request->tipo) {
            case 0: //persona que solicita el ingreso
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('solicitante_id', $request->solicitante)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            case 1: //bodeguero
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('per_atiende_id', $request->per_atiende)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)), //start date
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s") //end date
                        ]
                    )->orderBy('id', 'desc')->get(); //sort descending
                break;
            case 2: //motivos
                $results = TransaccionBodega::where('motivo_id', $request->motivo)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            case 3: //bodega o sucursal
                $request->sucursal != 0 ? $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('sucursal_id', $request->sucursal)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get() : $results = TransaccionBodega::whereIn('motivo_id', $motivos)->whereBetween(
                    'created_at',
                    [
                        date('Y-m-d', strtotime($request->fecha_inicio)),
                        $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                    ]
                )->orderBy('id', 'desc')->get();
                break;
            case 4: // devolucion
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('devolucion_id', $request->devolucion)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            case 5: //tarea
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('devolucion_id', $request->tarea)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            case 6: //transferencia
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('transferencia_id', $request->transferencia)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            default:
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->orderBy('id', 'desc')->get(); // todos los ingresos
                break;
        }
        return $results;
    }

    /**
     * La función "descontarMaterialesAsignados" se utiliza para actualizar el stock de materiales
     * de un empleado luego de devolver a bodega ciertos materiales asignados a una transacción.
     *
     * Probablemente, contenga información como el ID del producto, el nombre, la descripción y otros
     * detalles relevantes.
     * @throws Throwable
     */
    public function descontarMaterialesAsignados($listado, $transaccion, $detalle)
    {
        if ($transaccion->tarea_id) {
            if ($transaccion->devolucion_id) {
                $devolucion = Devolucion::find($transaccion->devolucion_id);
                MaterialEmpleadoTarea::descargarMaterialEmpleadoTarea($detalle->id, $transaccion->solicitante_id, $transaccion->tarea_id, $listado['cantidad'], $devolucion->cliente_id);
            } else MaterialEmpleadoTarea::descargarMaterialEmpleadoTarea($detalle->id, $transaccion->solicitante_id, $transaccion->tarea_id, $listado['cantidad'], $transaccion->cliente_id);
        } else { // Devolucion de stock personal
            if ($transaccion->devolucion_id) {
                $devolucion = Devolucion::find($transaccion->devolucion_id);
                MaterialEmpleado::descargarMaterialEmpleado($detalle->id, $transaccion->solicitante_id, $listado['cantidad'], $devolucion->cliente_id, $transaccion->cliente_id);
            } else {
                MaterialEmpleado::descargarMaterialEmpleado($detalle->id, $transaccion->solicitante_id, $listado['cantidad'], $transaccion->cliente_id, $transaccion->cliente_id);
            }
        }
    }

    public static function actualizarDevolucion($transaccion, $detalle, $cantidad)
    {
        $item_devolucion = DetalleDevolucionProducto::where('devolucion_id', $transaccion->devolucion_id)->where('detalle_id', $detalle->id)->first();
        if ($item_devolucion) {
            $item_devolucion->devuelto += $cantidad;
            $item_devolucion->save();
        } else {
            $item_devolucion = DetalleDevolucionProducto::create([
                'devolucion_id' => $transaccion->devolucion_id,
                'detalle_id' => $detalle->id,
                'cantidad' => $cantidad,
                'devuelto' => $cantidad,
            ]);
        }
        //aquí se verifica si se completaron los items de la devolución y se actualiza con parcial o completado según corresponda.
        DevolucionService::verificarItemsDevolucion($item_devolucion);
    }

    /**
     * @throws Throwable
     */
    public static function anularIngresoDevolucion($transaccion, $devolucion_id, $detalle_id, $cantidad)
    {
        $devolucion = Devolucion::find($devolucion_id);
        if ($transaccion->tarea_id) {
            MaterialEmpleadoTarea::cargarMaterialEmpleadoTareaPorAnulacionDevolucion($detalle_id, $devolucion->solicitante_id, $transaccion->tarea_id, $cantidad, $devolucion->cliente_id, $transaccion->proyecto_id, $transaccion->etapa_id);
        } else {
            MaterialEmpleado::cargarMaterialEmpleadoPorAnulacionDevolucion($detalle_id, $devolucion->solicitante_id, $cantidad, $devolucion->cliente_id);
        }
        $item = DetalleDevolucionProducto::where('devolucion_id', $devolucion_id)->where('detalle_id', $detalle_id)->first();
        if ($item) {
            $item->devuelto -= $cantidad;
            $item->save();
        } else throw new Exception('Ha ocurrido un error al intentar restar de la devolucion el item ' . $detalle_id);

        DevolucionService::verificarItemsDevolucion($item);
    }

    /**
     * La función "obtenerIngresos" recupera transacciones de ingresos según criterios específicos,
     * como rango de fechas y roles de usuario.
     *
     * @param string|Carbon|null $fecha_inicio El parámetro `fecha_inicio` representa la fecha de inicio a partir de la
     * que se quieren filtrar las transacciones. Si proporciona un valor para `fecha_inicio`, la
     * función solo recuperará transacciones que se crearon en esa fecha o después.
     * @param string|Carbon|null $fecha_fin El parámetro `fecha_fin` representa la fecha de
     * finalización del filtrado de transacciones. Se utiliza para recuperar transacciones que se
     * crearon en esta fecha o antes. Si se proporciona un valor para `fecha_fin`, la función filtrará
     * las transacciones según esta fecha de finalización.
     *
     * @return array|LengthAwarePaginator|\Illuminate\Support\Collection
     * ciertas condiciones. Las transacciones se filtran según el rol del usuario (ya sea `ROL_BODEGA`
     * o `ROL_ADMINISTRADOR` para un conjunto de condiciones, o `ROL_BODEGA_TELCONET` para otro
     * conjunto de condiciones).
     */
    public static function listar(Carbon|string $fecha_inicio = null, Carbon|string $fecha_fin = null, $paginate = false, $search = null)
    {
//        Log::channel('testing')->info('Log', ['TransaccionIngresoService::listar:', $fecha_inicio, $fecha_fin, $search]);
        $tipo_transaccion_str = TipoTransaccion::INGRESO;
        $pagination_service = new PaginationService();
        $tipo_transaccion = TipoTransaccion::where('nombre', $tipo_transaccion_str)->first();
        $ids_motivos = Motivo::where('tipo_transaccion_id', $tipo_transaccion->id)->get('id');
        $results = [];
        $filtrosAlgolia = "tipo_transaccion:$tipo_transaccion_str";

        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR])) {
            $query = TransaccionBodega::whereIn('motivo_id', $ids_motivos->toArray())
                ->when($fecha_inicio, function ($q) use ($fecha_inicio) {
                    $q->where('created_at', '>=', $fecha_inicio);
                })
                ->when($fecha_fin, function ($q) use ($fecha_fin) {
                    $q->where('created_at', '<=', $fecha_fin);
                })
                ->orderBy('id', 'desc');
            return buscarConAlgoliaFiltrado(TransaccionBodega::class, $query, 'id', $search, Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$paginate, $filtrosAlgolia);
//            if ($paginate) {
//                return $pagination_service->paginate($query, 100, request('page'));
//            } else {
////                Log::channel('testing')->info('Log', ['la queri es ', $query]);
//                return $query->get();
//            }
        }
        if (auth()->user()->hasRole([User::ROL_BODEGA_TELCONET])) {
            $query_telconet = TransaccionBodega::whereIn('motivo_id', $ids_motivos)
                ->where('cliente_id', ClientesCorporativos::TELCONET)
                ->when($fecha_inicio, function ($q) use ($fecha_inicio) {
                    $q->where('created_at', '>=', $fecha_inicio);
                })
                ->when($fecha_fin, function ($q) use ($fecha_fin) {
                    $q->where('created_at', '<=', $fecha_fin);
                })
                ->orderBy('id', 'desc');
            return buscarConAlgoliaFiltrado(TransaccionBodega::class, $query_telconet, 'id', $search, Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$paginate, $filtrosAlgolia);

        }
        return $results;
    }
}
