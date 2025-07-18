<?php

namespace Src\App\Bodega;

use App\Events\DevolucionCreadaEvent;
use App\Helpers\Filtros\FiltroSearchHelper;
use App\Http\Requests\DevolucionRequest;
use App\Http\Resources\DevolucionResource;
use App\Http\Resources\PedidoResource;
use App\Models\Autorizacion;
use App\Models\Condicion;
use App\Models\DetalleDevolucionProducto;
use App\Models\Devolucion;
use App\Models\EstadoTransaccion;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Config\Autorizaciones;
use Src\Config\Constantes;
use Src\Config\EstadosTransacciones;
use Src\Shared\Utils;

class DevolucionService
{

    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    private static function obtenerFiltrosIndice(string $estado)
    {
        $filtros = match ($estado) {
            'PENDIENTE' => [
                ['clave' => 'autorizacion', 'valor' => 'PENDIENTE'],
                ['clave' => 'estado', 'valor' => 'CREADA'],
            ],
            'APROBADO' => [
                ['clave' => 'autorizacion', 'valor' => 'APROBADO'],
                ['clave' => 'estado_bodega', 'valor' => EstadoTransaccion::PENDIENTE, 'operador'=> 'AND'],
            ],
            'PARCIAL' => [
                ['clave' => 'autorizacion', 'valor' => 'APROBADO'],
                ['clave' => 'estado_bodega', 'valor' => EstadoTransaccion::PARCIAL, 'operador'=> 'AND'],
            ],
            'CANCELADO' => [
                ['clave' => 'autorizacion', 'valor' => 'CANCELADO'],
                ['clave' => 'estado_bodega', 'valor' => '\"NO REALIZADA\"', 'operador'=> 'AND'],
            ],
            'COMPLETA' => [
                ['clave' => 'estado_bodega', 'valor' => EstadoTransaccion::COMPLETA],
            ],
            default => throw new Exception("El estado '$estado' no es válido para filtros."),
        };
        return FiltroSearchHelper::formatearFiltrosPorMotor($filtros);
    }

    /**
     * @throws Exception
     */
    public static function listar($request)
    {
        if ($request->estado) {
            $filtrosAlgolia = self::obtenerFiltrosIndice($request->estado);
            switch ($request->estado) {
                case 'PENDIENTE':
                    if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_AUDITOR, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE, User::ROL_BODEGA_TELCONET])) {
                        $query = Devolucion::where('autorizacion_id', 1)->where('estado', Devolucion::CREADA)->orderBy('updated_at', 'desc');//->get();
                    } else {
                        $query = Devolucion::where('autorizacion_id', 1)->where('estado', Devolucion::CREADA)
                            ->where(function ($query) {
                                $query->where('solicitante_id', auth()->user()->empleado->id)
                                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                            })->orderBy('updated_at', 'desc');//->get();
                    }
                    break;
                case 'APROBADO':
                    $query = self::obtenerRegistros(EstadoTransaccion::PENDIENTE);
                    break;
                case 'PARCIAL':
                    $query = self::obtenerRegistros(EstadoTransaccion::PARCIAL);
                    break;
                case 'CANCELADO':
                    if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_AUDITOR, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE, User::ROL_BODEGA_TELCONET])) {
                        $query = Devolucion::where('autorizacion_id', 3)->orWhere('estado_bodega', EstadoTransaccion::ANULADA)->orderBy('updated_at', 'desc');//->get();
                    } else {
                        $query = Devolucion::where('autorizacion_id', 3)->orWhere('estado_bodega', EstadoTransaccion::ANULADA)
                            ->where(function ($query) {
                                $query->where('solicitante_id', auth()->user()->empleado->id)
                                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                            })->orderBy('updated_at', 'desc');//->get();
                    }
                    break;
                case 'COMPLETA':
                    if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_AUDITOR, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE, User::ROL_BODEGA_TELCONET])) {
                        $query = Devolucion::where('estado_bodega', EstadoTransaccion::COMPLETA)->orderBy('updated_at', 'desc');//->get();
                    } else {
                        $query = Devolucion::where('estado_bodega', EstadoTransaccion::COMPLETA)
                            ->where(function ($query) {
                                $query->where('solicitante_id', auth()->user()->empleado->id)
                                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                            })->orderBy('updated_at', 'desc');//->get();
                    }
                    break;
                default:
                    $query = Devolucion::where('solicitante_id', auth()->user()->empleado->id)->orWhere('per_autoriza_id', auth()->user()->empleado->id)->orderBy('updated_at', 'desc');//->get();
            }

            return buscarConAlgoliaFiltrado(Devolucion::class, $query, 'id', $request->search, Constantes::PAGINATION_ITEMS_PER_PAGE,$request->page, !!$request->paginate, $filtrosAlgolia);
        } else {
            $results = Devolucion::when($request->fecha_inicio, function ($q) use ($request) {
                $q->where('created_at', '>=', $request->fecha_inicio);
            })
                ->when($request->fecha_fin, function ($q) use ($request) {
                    $q->where('created_at', '<=', $request->fecha_fin);
                })
                ->orderBy('updated_at', 'desc')->get();
        }
        return $results;
    }

    private static function obtenerRegistros(string $estado)
    {
        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_AUDITOR, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE, User::ROL_BODEGA_TELCONET])) {
            return Devolucion::where('autorizacion_id', 2)->where('estado_bodega', $estado)->orderBy('updated_at', 'desc');//->get(); //EstadoTransaccion::PENDIENTE
        } else {
            return Devolucion::where('autorizacion_id', 2)->where('estado_bodega', $estado) //EstadoTransaccion::PENDIENTE
                ->where(function ($query) {
                    $query->where('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                })->orderBy('updated_at', 'desc');//->get();
        }
    }

    /**
     * La función "verificarItemsDevolucion" comprueba si hay artículos en una devolución que no hayan
     * sido devueltos en su totalidad y actualiza el estado de la devolución en consecuencia.
     *
     * @param DetalleDevolucionProducto $detalleDevolucionProducto Es un objeto que
     * representa un detalle específico de la devolución de un producto. Probablemente contenga
     * información como el ID de devolución, el ID del producto, la cantidad devuelta y la cantidad ya
     * procesada.
     */
    public static function verificarItemsDevolucion(DetalleDevolucionProducto $detalleDevolucionProducto)
    {
        $resultados = DB::select('select count(*) as cantidad from detalle_devolucion_producto dpp where dpp.devolucion_id=' . $detalleDevolucionProducto->devolucion_id . ' and dpp.cantidad!=dpp.devuelto');
        $devolucion = Devolucion::find($detalleDevolucionProducto->devolucion_id);
        $detallesSinDevolver = $devolucion->detalles()->where('devuelto', 0)->count();

        if ($detallesSinDevolver === $devolucion->detalles()->count()) $devolucion->update(['estado_bodega' => EstadoTransaccion::PENDIENTE]);
        else {
            if ($resultados[0]->cantidad > 0) $devolucion->update(['estado_bodega' => EstadoTransaccion::PARCIAL]);
            else $devolucion->update(['estado_bodega' => EstadoTransaccion::COMPLETA]);
        }
    }

    public static function crearPedidoAutomatico($devolucion)
    {
        try {
            DB::beginTransaction();
            $carbon = new Carbon();
            $fecha_limite = $carbon->now()->addDays(2);
            $pedido = Pedido::create([
                'justificacion' => $devolucion->justificacion,
                'fecha_limite' => date('Y-m-d', strtotime($fecha_limite)),
                'solicitante_id' => $devolucion->solicitante_id,
                'responsable_id' => $devolucion->solicitante_id,
                'autorizacion_id' => $devolucion->autorizacion_id,
                'per_autoriza_id' => $devolucion->per_autoriza_id,
                'tarea_id' => $devolucion->tarea_id ? $devolucion->tarea_id : null,
                'sucursal_id' => $devolucion->sucursal_id,
                'estado_id' => EstadosTransacciones::PENDIENTE
            ]);
            $itemsDevolucion = DetalleDevolucionProducto::where('devolucion_id', $devolucion->id)->get();
            foreach ($itemsDevolucion as $item) {
                $pedido->detalles()->attach(
                    $item['detalle_id'],
                    [
                        'cantidad' => $item['cantidad'],
                        'solicitante_id' => $devolucion->solicitante_id
                    ]
                );
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR', $th->getMessage(), $th->getLine()]);
            throw $th;
        }
    }

    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public static function guardarDevolucion(DevolucionRequest $request) {
        // Log::channel('testing')->info('Log', ['recibido en el store de devoluciones', $request->all()]);
        $url = '/devoluciones';
        try {
            DB::beginTransaction();

            $devolucion = Devolucion::create(self::mapearRequest($request));
            $modelo = new DevolucionResource($devolucion);

            if ($request['misma_condicion']) {
                foreach ($request['listadoProductos'] as $listado) {
                    $devolucion->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad'], 'condicion_id' => $request['condicion']]);
                }
            } else {
                foreach ($request['listadoProductos'] as $listado) {
                    $condicion = Condicion::where('nombre', $listado['condiciones'])->first();
                    $devolucion->detalles()->attach(
                        $listado['id'],
                        [
                            'cantidad' => $listado['cantidad'],
                            'observacion' => array_key_exists('observacion', $listado) ?  $listado['observacion'] : null,
                            'condicion_id' => $condicion->id
                        ]
                    );
                }
            }


            DB::commit();
            $msg = 'Devolución N°' . $devolucion->id . ' ' . $devolucion->solicitante->nombres . ' ' . $devolucion->solicitante->apellidos . ' ha realizado una devolución en la sucursal ' . $devolucion->sucursal->lugar . ' . La autorización está ' . $devolucion->autorizacion->nombre;
            event(new DevolucionCreadaEvent($msg, $url, $devolucion, $devolucion->solicitante_id, $devolucion->per_autoriza_id, false));
            return $modelo;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages(['error' => [$e->getMessage() . '. ' . $e->getLine()]]);
        }
    }

    private static function mapearRequest(DevolucionRequest $request) {
        $datos = $request->validated();
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['stock_personal'] = $request['es_para_stock'];
        return $datos;
    }
}
