<?php

namespace Src\App;

use App\Http\Resources\DevolucionResource;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\Autorizacion;
use App\Models\Comprobante;
use App\Models\Devolucion;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\Motivo;
use App\Models\Pedido;
use App\Models\TransaccionBodega;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\App\Bodega\DevolucionService;
use Src\Config\EstadosTransacciones;
use Src\Shared\Utils;

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
        $results = [];
        $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
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
                return !$egreso->comprobante()->first()->firmada;
        })->count();
        $parciales = $todas->filter(function ($egreso) {
            return  $egreso->comprobante()->first()?->estado == EstadoTransaccion::PARCIAL;
        })->count();
        $completas = $todas->filter(function ($egreso) {
            $comprobante = $egreso->comprobante()->first();
            return  $comprobante?->firmada && $comprobante?->estado == TransaccionBodega::ACEPTADA;
        })->count();
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
    public static function obtenerInventarios($fecha_inicio, $fecha_fin)
    {
    }
}
