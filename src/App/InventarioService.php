<?php

namespace Src\App;

use App\Exports\KardexExport;
use App\Http\Resources\DevolucionResource;
use App\Http\Resources\ItemDetallePreingresoMaterialResource;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\Tareas\DetalleTransferenciaProductoEmpleadoResource;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\Autorizacion;
use App\Models\Comprobante;
use App\Models\ConfiguracionGeneral;
use App\Models\DetalleProductoTransaccion;
use App\Models\Devolucion;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\ItemDetallePreingresoMaterial;
use App\Models\Motivo;
use App\Models\Pedido;
use App\Models\Tareas\DetalleTransferenciaProductoEmpleado;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\App\Bodega\DevolucionService;
use Src\Config\EstadosTransacciones;
use Src\Shared\Utils;
use Dotenv\Exception\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;

class InventarioService
{


    /**
     * La función toma un término de búsqueda de una solicitud y devuelve artículos de inventario con
     * una descripción coincidente, filtrando opcionalmente artículos con cantidad cero.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que
     * representa una solicitud HTTP realizada al servidor. Contiene información sobre la solicitud,
     * como el método de solicitud, la URL, los encabezados y cualquier dato enviado con la solicitud.
     *
     * @return results los resultados de búsqueda basados en la solicitud dada.
     */
    public static function search(Request $request)
    {
        $search = $request->search;
        $results = $request->boolean('zeros') ? Inventario::with('detalle')
            ->whereHas('detalle', function ($query) use ($search) {
                $query->where('descripcion', 'LIKE', '%' . $search . '%');
                $query->orWhere('serial', 'LIKE', '%' . $search . '%');
            })
            ->when($request->cliente_id, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente_id);
            })
            ->when($request->sucursal_id, function ($query) use ($request) {
                $query->where('sucursal_id', $request->sucursal_id);
            })
            ->when($request->condicion_id, function ($query) use ($request) {
                $query->where('condicion_id', $request->condicion_id);
            })
            ->get() :
            Inventario::with('detalle')->whereHas('detalle', function ($query) use ($search) {
                $query->where('descripcion', 'LIKE', '%' . $search . '%');
                $query->orWhere('serial', 'LIKE', '%' . $search . '%');
            })->when($request->cliente_id, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente_id);
            })
            ->when($request->sucursal_id, function ($query) use ($request) {
                $query->where('sucursal_id', $request->sucursal_id);
            })
            ->when($request->condicion_id, function ($query) use ($request) {
                $query->where('condicion_id', $request->condicion_id);
            })->where('cantidad', '<>', 0)->get();
        return $results;
    }

    /**
     * La función recupera artículos de inventario en función de ciertas condiciones, con una opción
     * para incluir artículos con cantidad cero.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que se
     * utiliza para recuperar datos de la solicitud HTTP. Contiene información como el método de
     * solicitud, los encabezados y los datos de entrada. En este caso, se utiliza para recuperar
     * parámetros de consulta como 'ceros', 'cliente_id', 'sucursal_id', y 'condicion_id'.
     *
     * @return Collection results una colección de resultados de la base de datos. Los resultados se filtran en función de
     * los valores de los parámetros `cliente_id`, `sucursal_id` y `condicion_id` de la solicitud. Si
     * el parámetro `ceros` es verdadero, se devuelven todos los resultados. Si `ceros` es falso, solo
     * se devuelven resultados con un valor de `cantidad` distinto de 0.
     */
    public static function todos(Request $request)
    {
        $results = $request->boolean('zeros') ?
            Inventario::when($request->cliente_id, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente_id);
            })
            ->when($request->sucursal_id, function ($query) use ($request) {
                $query->where('sucursal_id', $request->sucursal_id);
            })
            ->when($request->condicion_id, function ($query) use ($request) {
                $query->where('condicion_id', $request->condicion_id);
            })
            ->get() :
            Inventario::when($request->cliente_id, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente_id);
            })
            ->when($request->sucursal_id, function ($query) use ($request) {
                $query->where('sucursal_id', $request->sucursal_id);
            })
            ->when($request->condicion_id, function ($query) use ($request) {
                $query->where('condicion_id', $request->condicion_id);
            })->where('cantidad', '<>', 0)->get();
        return $results;
    }

    public static function obtenerDashboard(Request $request)
    {
        $fecha_inicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio)->startOfDay() : Carbon::now();
        $fecha_fin = $request->fecha_fin ? Carbon::parse($request->fecha_fin)->endOfDay() : Carbon::now();
        switch ($request->tipo) {
            case 'INGRESO':
                $results = self::obtenerIngresos($fecha_inicio, $fecha_fin);
                $results['todas'] = TransaccionBodegaResource::collection($results['todas']);
                break;
            case 'EGRESO':
                $results = self::obtenerEgresos($fecha_inicio, $fecha_fin);
                $results['todas'] = TransaccionBodegaResource::collection($results['todas']);
                break;
            case 'DEVOLUCION':
                $results = self::obtenerDevoluciones($fecha_inicio, $fecha_fin);
                $results['todas'] = DevolucionResource::collection($results['todas']);
                break;
            case 'PEDIDO':
                $results = self::obtenerPedidos($fecha_inicio, $fecha_fin);
                $results['todas'] = PedidoResource::collection($results['todas']);
                break;
            default:
                // aqui se lanzará un error
                throw new Exception('Error con el tipo obtenido, no concuerda con ninguna opción de tipo de dashboard');
        }

        return $results;
    }

    public static function obtenerIngresos($fecha_inicio, $fecha_fin)
    {
        $servicioIngreso = new TransaccionBodegaIngresoService();

        // Log::channel('testing')->info('Log', ['fechas de filtrado en obtener ingresos:', $fecha_inicio, $fecha_fin]);
        $todas = $servicioIngreso->listar($fecha_inicio, $fecha_fin);
        $resultados_agrupados = $todas->groupBy('motivo_id');
        // Log::channel('testing')->info('Log', ['resultados agrupados:', $resultados_agrupados]);
        $data = []; //Claves y valores a graficarse en el pie
        foreach ($resultados_agrupados as $motivo_id => $r) {
            // Log::channel('testing')->info('Log', ['Motivo:', Motivo::find($motivo_id)->nombre, $r->count()]);
            $data[Motivo::find($motivo_id)->nombre] = $r->count();
        }
        $tituloGrafico = 'Ingresos a bodega';
        $graficos = [];

        //Ordenamos los datos en orden descendente
        arsort($data);

        // Definimos un límite superior para la cantidad de elementos a mostrar directamente
        $limit = 4;
        //Creamos dos arreglos para almacenar los datos mostrados y los datos agrupados en otros
        $displayedData  = [];
        $othersData  = [];

        // Iteramos sobre los datos y los distribuimos entre los mostrados y los agrupados en "otros"
        foreach ($data as $key => $value) {
            if (count($displayedData) < $limit) {
                $displayedData[$key] = $value;
            } else {
                $othersData[$key] = $value;
            }
        }

        // Si hay elementos agrupados en "otros", sumamos sus valores
        if (!empty($othersData)) {
            $othersValue = array_sum($othersData);
            $displayedData['OTROS'] = $othersValue;
        }

        // Log::channel('testing')->info('Log', ['Displayed data:', $displayedData]);
        // Log::channel('testing')->info('Log', ['labels:', array_keys($displayedData)]);
        // Log::channel('testing')->info('Log', ['values:', array_values($displayedData)]);

        //Configuramos el primer grafico
        $graficoIngresos = Utils::configurarGrafico(1, 'TODOS', 'Ingresos a bodega por motivos frecuentes', array_keys($displayedData), Utils::coloresAleatorios(), $tituloGrafico, array_values($displayedData));
        array_push($graficos, $graficoIngresos);

        //Configuramos el segundo grafico
        $graficoOtros = Utils::configurarGrafico(2, 'TODOS', 'Ingresos a bodega por motivos poco frecuentes', array_keys($othersData), Utils::colorDefault(), $tituloGrafico, array_values($othersData));
        array_push($graficos, $graficoOtros);

        return compact(
            'graficos',
            'todas'
        );
    }
    public static function obtenerEgresos($fecha_inicio, $fecha_fin)
    {
        $request = new Request();
        $request['fecha_inicio'] = $fecha_inicio;
        $request['fecha_fin'] = $fecha_fin;
        $servicioEgreso = new TransaccionBodegaEgresoService();
        $todas = $servicioEgreso->listar($request);
        $resultados_agrupados = $todas->groupBy('motivo_id');
        $data = [];
        foreach ($resultados_agrupados as $motivo_id => $r) {
            $data[Motivo::find($motivo_id)->nombre] = $r->count();
        }
        $tituloGrafico = 'Egresos de bodega';
        $graficos = [];

        //Ordenamos los datos en orden descendente
        arsort($data);
        // Log::channel('testing')->info('Log', ['Data a mostrar:', $data]);

        // Definimos un límite superior para la cantidad de elementos a mostrar directamente
        $limit = 4;
        //Creamos dos arreglos para almacenar los datos mostrados y los datos agrupados en otros
        $displayedData  = [];
        $othersData  = [];

        // Iteramos sobre los datos y los distribuimos entre los mostrados y los agrupados en "otros"
        foreach ($data as $key => $value) {
            if (count($displayedData) < $limit) {
                $displayedData[$key] = $value;
            } else
                $othersData[$key] = $value;
        }

        // Si hay elementos agrupados en "otros", sumamos sus valores
        if (!empty($othersData)) {
            $othersValue = array_sum($othersData);
            $displayedData['OTROS'] = $othersValue;
        }

        //Configuramos el primer grafico
        $graficoEgresos = Utils::configurarGrafico(1, 'TODOS', 'Egresos de bodega por motivos frecuentes', array_keys($displayedData), Utils::coloresAleatorios(), $tituloGrafico, array_values($displayedData));
        array_push($graficos, $graficoEgresos);

        //Configuramos el segundo grafico
        if (count($othersData) > 0) {
            $graficoOtros = Utils::configurarGrafico(2, 'OTROS', 'Egresos de bodega por motivos poco frecuentes', array_keys($othersData), Utils::colorDefault(), $tituloGrafico, array_values($othersData));
            array_push($graficos, $graficoOtros);
        }

        $pendientes = $todas->filter(function ($egreso) {
            if (!is_null($egreso->comprobante()->first()))
                return !$egreso->comprobante()->first()->firmada && $egreso->comprobante()->first()->estado === EstadoTransaccion::PENDIENTE;
        })->count();
        $parciales = $todas->filter(function ($egreso) {
            return  $egreso->comprobante()->first()?->estado == EstadoTransaccion::PARCIAL;
        })->count();
        $completas = $todas->filter(function ($egreso) {
            $comprobante = $egreso->comprobante()->first();
            return  $comprobante?->firmada && $comprobante?->estado == TransaccionBodega::ACEPTADA;
        })->count();
        $completasSinComprobante = $todas->filter(function ($transaccion) {
            return !$transaccion->comprobante()->exists() && $transaccion->estado_id == 2;
        })->count();
        $completas += $completasSinComprobante;
        $anuladas = $todas->filter(function ($egreso) {
            return $egreso->estado_id == 4;
        })->count();

        //Configuramos el tercer grafico
        $labels = [EstadoTransaccion::PENDIENTE, EstadoTransaccion::PARCIAL, EstadoTransaccion::COMPLETA, EstadoTransaccion::ANULADA];
        $data = [$pendientes, $parciales, $completas, $anuladas];
        $graficoEstados = Utils::configurarGrafico(3, 'ESTADOS', 'Egresos de bodega por estados', $labels, Utils::coloresEstadosEgresos(), $tituloGrafico, $data);
        array_push($graficos, $graficoEstados);

        return compact(
            'graficos',
            'todas',
            'pendientes',
            'parciales',
            'completas',
            'anuladas',
            'completasSinComprobante',
        );
    }
    public static function obtenerDevoluciones($fecha_inicio, $fecha_fin)
    {
        $request = new Request();
        $request['fecha_inicio'] = $fecha_inicio;
        $request['fecha_fin'] = $fecha_fin;
        $servicioDevolucion = new DevolucionService();
        $todas = $servicioDevolucion->listar($request);
        $tituloGrafico = 'Devoluciones Realizadas';
        $graficos = [];
        $pendientes = $todas->filter(function ($devolucion) {
            return $devolucion->autorizacion_id == 1 && $devolucion->estado == Devolucion::CREADA;
        })->count();
        $aprobadas = $todas->filter(function ($devolucion) {
            return $devolucion->autorizacion_id == 2 && $devolucion->estado_bodega == EstadoTransaccion::PENDIENTE;
        })->count();
        $parciales = $todas->filter(function ($devolucion) {
            return $devolucion->autorizacion_id == 2 && $devolucion->estado_bodega == EstadoTransaccion::PARCIAL;
        })->count();
        $canceladas = $todas->filter(function ($devolucion) {
            return $devolucion->autorizacion_id == 3 || $devolucion->estado_bodega == EstadoTransaccion::ANULADA;
        })->count();
        $completas = $todas->filter(function ($devolucion) {
            return $devolucion->estado_bodega == EstadoTransaccion::COMPLETA;
        })->count();
        $labels = [EstadoTransaccion::PENDIENTE, Autorizacion::APROBADO, EstadoTransaccion::PARCIAL, Autorizacion::CANCELADO, EstadoTransaccion::COMPLETA];
        $data = [$pendientes, $aprobadas, $parciales, $canceladas, $completas];
        $grafico = Utils::configurarGrafico(1, 'ESTADOS', 'Estados de devoluciones de bodega', $labels, Utils::coloresEstadosDevoluciones(), $tituloGrafico, $data);
        array_push($graficos, $grafico);

        return compact(
            'graficos',
            'todas',
            'pendientes',
            'aprobadas',
            'parciales',
            'canceladas',
            'completas',
        );
    }
    public static function obtenerPedidos($fecha_inicio, $fecha_fin)
    {
        $todas = Pedido::where('created_at', '>=', $fecha_inicio)->where('created_at', '<=', $fecha_fin)->orderBy('updated_at', 'desc')->get();
        $tituloGrafico = 'Pedidos Realizados';
        $graficos = [];
        $pendientes = $todas->filter(function ($pedido) {
            return $pedido->autorizacion_id == 1;
        })->count();
        $aprobadas = $todas->filter(function ($pedido) {
            return $pedido->autorizacion_id == 2 && $pedido->estado_id == 1;
        })->count();
        $parciales = $todas->filter(function ($pedido) {
            return $pedido->estado_id == 3;
        })->count();
        $canceladas = $todas->filter(function ($pedido) {
            return $pedido->autorizacion_id == 3;
        })->count();
        $completas = $todas->filter(function ($pedido) {
            return $pedido->estado_id == 2;
        })->count();

        $labels = [EstadoTransaccion::PENDIENTE, Autorizacion::APROBADO, EstadoTransaccion::PARCIAL, Autorizacion::CANCELADO, EstadoTransaccion::COMPLETA];
        $data = [$pendientes, $aprobadas, $parciales, $canceladas, $completas];
        $grafico = Utils::configurarGrafico(1, 'ESTADOS', 'Pedidos realizados a bodega', $labels, Utils::coloresEstadosDevoluciones(), $tituloGrafico, $data);
        array_push($graficos, $grafico);

        return compact(
            'graficos',
            'todas',
            'pendientes',
            'aprobadas',
            'parciales',
            'canceladas',
            'completas',
        );
    }
    public static function obtenerInventarios($fecha_inicio, $fecha_fin) {}

    public function kardex(int $detalle_id, $fecha_inicio, $fecha_fin, $tipo_rpt = null, int $sucursal_id = null)
    {
        $fecha_fin = Carbon::parse($fecha_fin)->endOfDay();
        // Log::channel('testing')->info('Log', ['Request kardex', $request->all()]);
        $configuracion = ConfiguracionGeneral::first();
        // $estadoCompleta = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        $results = [];
        $preingresos = [];
        $transferencias = [];
        $cont = 0;
        $cantAudit = 0;
        $row = [];
        $tipoTransaccion = TipoTransaccion::where('nombre', 'INGRESO')->first();
        $ids_motivos_ingresos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $ids_itemsInventario = Inventario::where('detalle_id', $detalle_id)
            ->when($sucursal_id, function ($q) use ($sucursal_id, $fecha_inicio, $fecha_fin) {
                $q->where('sucursal_id', $sucursal_id);
            })->orderBy('updated_at', 'desc')->get('id');
        if ($fecha_inicio && $fecha_fin) {
            $movimientos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_itemsInventario)
                ->whereBetween('detalle_producto_transaccion.created_at', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))])
                ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        }
        if ($fecha_inicio && !$fecha_fin) {
            $movimientos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_itemsInventario)
                ->whereBetween('detalle_producto_transaccion.created_at', [date('Y-m-d', strtotime($fecha_inicio)), date("Y-m-d h:i:s")])
                ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        }
        if (!$fecha_inicio && !$fecha_fin) {
            $movimientos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_itemsInventario)
                ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        }
        //Obtener los ingresos y egresos anulados
        $movimientosIngresosAnulados = DetalleProductoTransaccion::withWhereHas('transaccion', function ($query) use ($ids_motivos_ingresos, $movimientos) {
            $query->whereIn('motivo_id', $ids_motivos_ingresos)
                ->whereIn('id', $movimientos->pluck('transaccion_id'))
                ->where('estado_id', EstadosTransacciones::ANULADA);
        })
            ->whereIn('inventario_id', $ids_itemsInventario)
            ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        $movimientosEgresosAnulados = DetalleProductoTransaccion::withWhereHas('transaccion', function ($query) use ($ids_motivos_ingresos, $movimientos) {
            $query->where('estado_id', EstadosTransacciones::ANULADA)
                ->whereIn('id', $movimientos->pluck('transaccion_id'))
                ->whereNotIn('motivo_id', $ids_motivos_ingresos);
        })
            ->whereIn('inventario_id', $ids_itemsInventario)
            ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();

        foreach ($movimientos as $movimiento) {
            // Log::channel('testing')->info('Log', [$cont, 'Movimiento', $movimiento]);
            // Log::channel('testing')->info('Log', [$cont, 'Movimiento', $movimiento]);
            if ($cont == 0) {
                $audit = Audit::where('auditable_id', $movimiento->inventario_id)
                    ->where('auditable_type', Inventario::class)
                    ->whereBetween('updated_at', [
                        Carbon::parse($movimiento->created_at)->subSecond(2),
                        Carbon::parse($movimiento->created_at)->addSecond(2),
                    ])
                    ->first();
                // Log::channel('testing')->info('Log', ['audit', $audit]);
                if ($audit) $cantAudit = count($audit->old_values) > 0 ? $audit->old_values['cantidad'] : 0;
            }
            $row['id'] = $movimiento->inventario->detalle->id;
            $row['id'] = $cont + 1;
            $row['detalle'] = $movimiento->inventario->detalle->descripcion;
            $row['detalle_producto_id'] = $movimiento->inventario->detalle_id;
            $row['num_transaccion'] = $movimiento->transaccion->id;
            $row['motivo'] = $movimiento->transaccion->motivo->nombre;
            $row['cliente_id'] = $movimiento->transaccion->cliente_id;
            $row['responsable_id'] = $movimiento->transaccion->responsable_id;
            $row['tipo'] = $movimiento->transaccion->motivo->tipoTransaccion->nombre;
            $row['sucursal'] = $movimiento->inventario->sucursal?->lugar;
            $row['condicion'] = $movimiento->inventario->condicion->nombre;
            $row['cantidad'] = $movimiento->recibido; //cantidad_inicial;
            $row['cant_anterior'] = $cont == 0 ? $cantAudit : $row['cant_actual'];
            $row['cant_actual'] = ($row['tipo'] == 'INGRESO' ? $row['cant_anterior'] + $movimiento->cantidad_inicial : $row['cant_anterior'] - $movimiento->cantidad_inicial);
            // $row['cant_actual'] = $cont == 0 ? $movimiento->cantidad_inicial : ($row['tipo'] == 'INGRESO' ? $row['cant_actual'] + $movimiento->cantidad_inicial : $row['cant_actual'] - $movimiento->cantidad_inicial);
            $row['fecha'] = date('d/m/Y', strtotime($movimiento->created_at));
            $row['fecha_hora'] = date('Y-m-d H:i:s', strtotime($movimiento->created_at));
            $row['comprobante_firmado'] = !!Comprobante::where('transaccion_id', $movimiento->transaccion->id)->first()?->firmada;
            $row['estado_comprobante'] = TransaccionBodega::obtenerComprobante($movimiento->transaccion->id)?->estado;
            $row['codigo_permiso_traslado'] = $movimiento->transaccion->codigo_permiso_traslado;
            $results[$cont] = $row;
            $cont++;
            //Aqui se verifica si contiene el id actual la collection de ingresos anulados
            $ingresoCoincidente = $movimientosIngresosAnulados->firstWhere('id', $movimiento->id);
            if ($ingresoCoincidente !== null) {
                //Se ingresa el movimiento como anulado y se resta al inventario
                $row['id'] = $cont + 1;
                $row['detalle'] = $ingresoCoincidente->inventario->detalle->descripcion;
                $row['num_transaccion'] = $ingresoCoincidente->transaccion->id;
                $row['motivo'] = $ingresoCoincidente->transaccion->motivo->nombre;
                $row['tipo'] = 'ANULACION';
                $row['sucursal'] = $ingresoCoincidente->inventario->sucursal->lugar;
                $row['cantidad'] = $ingresoCoincidente->cantidad_inicial;
                $row['cant_anterior'] = $cont == 0 ? $cantAudit : $row['cant_actual'];
                $row['cant_actual'] = $row['cant_anterior'] - $movimiento->cantidad_inicial;
                $row['fecha'] = date('d/m/Y', strtotime($movimiento->created_at));
                $results[$cont] = $row;
                $cont++;
            }
            //Aqui se verifica si contiene el id actual la collection de egresos anulados
            $egresoCoincidente = $movimientosEgresosAnulados->firstWhere('id', $movimiento->id);
            if ($egresoCoincidente !== null) {
                //Se ingresa el movimiento como anulado y se suma al inventario
                $row['id'] = $cont + 1;
                $row['detalle'] = $egresoCoincidente->inventario->detalle->descripcion;
                $row['num_transaccion'] = $egresoCoincidente->transaccion->id;
                $row['motivo'] = $egresoCoincidente->transaccion->motivo->nombre;
                $row['tipo'] = 'ANULACION';
                $row['sucursal'] = $egresoCoincidente->inventario->sucursal->lugar;
                $row['cantidad'] = $egresoCoincidente->cantidad_inicial;
                $row['cant_anterior'] = $cont == 0 ? $cantAudit : $row['cant_actual'];
                $row['cant_actual'] = $row['cant_anterior'] + $egresoCoincidente->cantidad_inicial;
                $row['fecha'] = date('d/m/Y', strtotime($movimiento->created_at));
                $results[$cont] = $row;
                $cont++;
            }
        }

        //Aqui se filtra los preingresos donde ha sido visto el ítem
        $preingresos = ItemDetallePreingresoMaterial::where('detalle_id', $detalle_id)->get();
        $preingresos = ItemDetallePreingresoMaterialResource::collection($preingresos);

        //Aquí se filtra las transferencias de productos
        $transferencias = DetalleTransferenciaProductoEmpleado::where('detalle_producto_id', $detalle_id)
            ->when($fecha_inicio, function ($q) use ($fecha_inicio) {
                $q->where('created_at', '>=', $fecha_inicio);
            })
            ->when($fecha_fin, function ($q) use ($fecha_fin) {
                $q->where('created_at', '<=', $fecha_fin);
            })->orderBy('created_at', 'desc')->get();
        $transferencias = DetalleTransferenciaProductoEmpleadoResource::collection($transferencias);

        rsort($results); //aqui se ordena el array en forma descendente
        Log::channel('testing')->info('Log', ['Results', $results]);
        switch ($tipo_rpt) {
            case 'excel':
                try {
                    return Excel::download(new KardexExport(collect($results)), 'kardex.xlsx');
                } catch (Exception $ex) {
                    Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                    throw ValidationException::withMessages([
                        'error' => [$ex->getMessage()],
                    ]);
                }
                break;
            case 'pdf':
                try {
                    $pdf = Pdf::loadView('bodega.reportes.kardex', compact('results', 'configuracion'));
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->render();
                    $file = $pdf->output();
                    return $file;
                } catch (Exception $ex) {
                    Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                    throw ValidationException::withMessages([
                        'error' => [$ex->getMessage()],
                    ]);
                }
                break;
            default:
                return compact('results', 'preingresos', 'transferencias');
                // return response()->json(compact('results', 'preingresos', 'transferencias'));
        }
    }
}
