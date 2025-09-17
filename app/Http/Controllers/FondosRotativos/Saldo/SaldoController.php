<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Exports\AcreditacionesExport;
use App\Exports\ConsolidadoExport;
use App\Exports\EstadoCuentaExport;
use App\Exports\GastoConsolidadoExport;
use App\Exports\GastoFiltradoExport;
use App\Exports\SaldoActualExport;
use App\Exports\TranferenciaSaldoExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Gastos\GastoResource;
use App\Http\Resources\FondosRotativos\Saldo\SaldoResource;
use App\Models\Canton;
use App\Models\Cliente;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Gasto\SubDetalleViatico;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\Saldo;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use http\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\FondosRotativos\SaldoService;
use Src\Shared\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class SaldoController extends Controller
{
    private string $entidad = 'saldo';
    private ReportePdfExcelService $reporteService;
    private SaldoService $saldoService;
    private const ACREDITACION = 1;
    private const GASTO = 2;
    private const CONSOLIDADO = 3;
    private const ESTADO_CUENTA = 4;
    private const TRANSFERENCIA = 5;
    private const GASTO_IMAGEN = 6;
    //Diccionario de filtros de gasto
    private const PROYECTO = 1;
    private const TAREA = 2;
    private const DETALLE = 3;
    private const SUBDETALLE = 4;
    private const AUTORIZADORGASTO = 5;
    private const EMPLEADO = 6;
    private const RUC = 7;
//    private const SINFACTURA = 8;
    private const CIUDAD = 9;
    private const GRUPO = 10;
    private const CLIENTE = 11;


    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->saldoService = new SaldoService();
        $this->middleware('can:puede.ver.saldo')->only('index', 'show');
        $this->middleware('can:puede.crear.saldo')->only('store');
        $this->middleware('can:puede.editar.saldo')->only('update');
        $this->middleware('can:puede.eliminar.saldo')->only('update');
        $this->middleware('can:puede.ver.reporte_saldo_actual')->only('saldo_actual');
        $this->middleware('can:puede.ver.reporte_consolidado')->only('consolidado');
    }

    /**
     * It gets all the data from the table, filters it, and returns it as a json response
     *
     * @return JsonResponse collection of SaldoGrupoResource
     */
    public function index()
    {
        $results = Saldo::with('usuario')->ignoreRequest(['campos'])->filter()->get();
        $results = SaldoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * It takes the date of the current day, and then it calculates the start and end date of the week.
     * </code>
     *
     * @param Request $request
     * @return JsonResponse <code>{</code>
     *     "mensaje": "Registro creado correctamente",
     *     "modelo": {
     *         "id": 1,
     *         "id_usuario": 1,
     *         "saldo_anterior": 0,
     *         "saldo_actual": 0,
     *         "fecha": "2019"
     */

    public function store(Request $request)
    {
        $array_dias['Sunday'] = 0;
        $array_dias['Monday'] = 1;
        $array_dias['Tuesday'] = 2;
        $array_dias['Wednesday'] = 3;
        $array_dias['Thursday'] = 4;
        $array_dias['Friday'] = 5;
        $array_dias['Saturday'] = 6;

        $dia_actual = $array_dias[date('l', strtotime($request->fecha))];

        $rest = $dia_actual + 1;
        $sum = 5 - $dia_actual;
//        $datos_usuario_add_saldo = User::where('id', $request->empleado)->first();
        $datos_saldo_inicio_sem = SaldoGrupo::where('id_usuario', $request->empleado)->orderBy('id', 'desc')->first();
//        $user = Auth::user();
        $fechaIni = date("Y-m-d", strtotime($request->fecha . "-$rest days"));
        $fechaFin = date("Y-m-d", strtotime($request->fecha . "+$sum days"));
        //Adaptacion de campos
        $datos = $request->all();
        $datos['id_usuario'] = $request->empleado;
        $datos['saldo_anterior'] = $datos_saldo_inicio_sem != null ? $datos_saldo_inicio_sem->saldo_actual : 0;
        $datos['fecha'] = date('Y-m-d H:i:s');
        $datos['fecha_inicio'] = $fechaIni;
        $datos['fecha_fin'] = $fechaFin;
        $modelo = SaldoGrupo::create($datos);
        $modelo = new SaldoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * It returns a JSON response with a single SaldoGrupo model
     *
     * @param int $id The id of the SaldoGrupo you want to show.
     *
     * @return JsonResponse JSON object with the data from the database.
     */
    public function show(int $id)
    {
        $SaldoGrupo = Saldo::where('id', $id)->first();
        $modelo = new SaldoResource($SaldoGrupo);
        return response()->json(compact('modelo'));
    }

    /**
     * It deletes a record from the database
     *
     * @param SaldoGrupo $SaldoGrupo The model
     *
     * @return JsonResponse response is a JSON object with the message.
     */
    public function destroy(SaldoGrupo $SaldoGrupo)
    {
        $SaldoGrupo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * It returns the last record of the table SaldoGrupo where the id_usuario is equal to the id passed
     * as a parameter.
     * If there is no record, it returns 0
     *
     * @param int $id The id of the user
     *
     * @return JsonResponse last record of the table SaldoGrupo where the id_usuario is equal to the id passed as
     * a parameter.
     */
    public function saldoActualUsuario(int $id)
    {
        $saldo_actual = Saldo::where('empleado_id', $id)->orderBy('id', 'desc')->first();
        $saldo_actual = $saldo_actual != null ? round($saldo_actual->saldo_actual, 2) : 0;

        return response()->json(compact('saldo_actual'));
    }

    /**
     * It's a function that returns a PDF or Excel file depending on the parameter that is passed to it
     *
     * @param Request $request The request object.
     * @param mixed $tipo is the type of report, it can excel or pdf
     *
     * @return Response|BinaryFileResponse file, but the file is not being downloaded.
     * @throws ValidationException
     */
    public function saldoActual(Request $request, mixed $tipo)
    {
        try {
            $usuario = Auth::user();
            $id = $request->empleado != null ? $request->empleado : 0;
            if ($usuario->hasRole([User::ROL_COORDINADOR, User::ROL_CONTABILIDAD, User::ROL_ADMINISTRADOR])) {
                $saldos_actual_user = $request->empleado == null ?
                    Saldo::with('empleado')->whereIn('id', function ($sub) {
                        $sub->selectRaw('max(id)')->from('fr_saldos')->groupBy('empleado_id');
                    })->get()
                    : Saldo::with('empleado')->where('empleado_id', $id)->orderBy('id', 'desc')->first();
            } else {
                $empleados = Empleado::where('jefe_id', $usuario->empleado->id)->get(['id', 'nombres', 'apellidos'])->pluck('id');
                $saldos_actual_user = $request->empleado == null ?
                    Saldo::with('empleado')->whereIn('id', function ($sub) {
                        $sub->selectRaw('max(id)')->from('fr_saldos')->groupBy('empleado_id');
                    })->whereIn('empleado_id', $empleados)
                        ->get()
                    : Saldo::with('usuario')->where('empleado_id', $id)->orderBy('id', 'desc')->first();
            }
            $tipo_reporte = $request->empleado != null ? 'usuario' : 'todos';
            $results = Saldo::empaquetarListado($saldos_actual_user, $tipo_reporte);
            $nombre_reporte = 'reporte_saldoActual';
            $reportes = ['saldos' => $results];
            $vista = 'exports.reportes.reporte_saldo_actual';
            $export_excel = new SaldoActualExport($reportes);
            return $this->reporteService->imprimirReporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw  Utils::obtenerMensajeErrorLanzable($e, 'Error al obtener el saldo actual');
        }

    }


    /**
     * La función "consolidado" en PHP procesa diferentes tipos de informes según el tipo de saldo
     * solicitado.
     *
     * @param Request $request La función `consolidado` en el fragmento de código que proporcionaste
     * toma dos parámetros: `` y ``.
     * @param mixed $tipo_reporte La función `consolidado` maneja diferentes
     * tipos de reportes los cuales pueden ser PDF y Excel
     *
     * @return BinaryFileResponse|Response función `consolidado` devuelve el resultado de una de las siguientes funciones según
     * el valor de `tipo_saldo`:
     * - Función `acreditación` si el valor es `ACREDITACIÓN`
     * - Función `gasto` si el valor es `GASTO` o `GASTO_IMAGEN`
     * - Función `reporteConsolidado` si el valor es `CONSOLIDADO`
     * - Función `reporteTransferencia` si el valor es `TRANSFERENCIA`
     * @throws ValidationException|Throwable
     */
    public function consolidado(Request $request, mixed $tipo_reporte)
    {
        try {
            switch ($request->tipo_saldo) {
                case self::ACREDITACION :
                    return $this->acreditacion($request, $tipo_reporte);
                case self::GASTO :
                    return $this->gasto($request, $tipo_reporte);
                case self::CONSOLIDADO : //3
                    return $this->reporteConsolidado($request, $tipo_reporte);
                case self::ESTADO_CUENTA : //4
                    return $this->reporteEstadoCuenta($request, $tipo_reporte);
                case self::TRANSFERENCIA :
                    return $this->reporteTransferencia($request, $tipo_reporte);
                case self::GASTO_IMAGEN :
                    return $this->gasto($request, $tipo_reporte, true);
                default :
                    throw new Exception("No hay un reporte para este tipo de filtro");
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    /**
     * La función `consolidadoFiltrado` en PHP toma una solicitud y un tipo de informe, luego, según el
     * tipo de saldo solicitado, llama a diferentes métodos para generar y devolver informes específicos.
     *
     * @param Request $request La función `consolidadoFiltrado` toma dos parámetros: `` de tipo
     * `Request` y ``.
     * @param string $tipo_reporte La función `consolidadoFiltrado` toma dos parámetros: `` de tipo
     * `Request` y `` de tipo no especificado. Luego, la función usa una declaración de cambio
     * para determinar la acción en función del valor de `->tipo_saldo`. Dependiendo del valor,
     *
     * @return Response|BinaryFileResponse método `consolidadoFiltrado` está devolviendo el resultado de uno de los siguientes
     * métodos basados en el valor de `->tipo_saldo`:
     * - `acreditacion()` si el valor es `self::ACREDITACION`
     * - `gastoFiltrado()` si el valor es `self::GASTO
     * - `reporteConsolidado()` si el valor es `self::CONSOLIDADO
     * - `reporteEstadoCuenta()` si el valor es `self::ESTADO_CUENTA
     * @throws ValidationException
     */
    public function consolidadoFiltrado(Request $request, string $tipo_reporte)
    {
        try {
            switch ($request->tipo_saldo) {
                case self::ACREDITACION:
                    return $this->acreditacion($request, $tipo_reporte);
                case self::GASTO: //2
                    return $this->gastoFiltrado($request, $tipo_reporte);
                case self::CONSOLIDADO:
                    return $this->reporteConsolidado($request, $tipo_reporte);
                case self::ESTADO_CUENTA:
                    return $this->reporteEstadoCuenta($request, $tipo_reporte);
                case self::TRANSFERENCIA:
                    return $this->reporteTransferencia($request, $tipo_reporte);
                case self::GASTO_IMAGEN:
                    return $this->gastoFiltrado($request, $tipo_reporte, true);
                case 7:
                    return $this->fotografiasOM($request, $tipo_reporte);
                default:
                    throw  new Exception("No hay un reporte para este tipo de filtro");
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error consolidadoFiltrado', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        } catch (Throwable $e) {
            throw Utils::obtenerMensajeErrorLanzable($e, 'consolidadoFiltrado');
        }
    }

    /**
     * It's a function that receives a request, a type, and returns a report
     *
     * @param Request $request The request object.
     * @param string $tipo The type of report you want to generate, in this case it is pdf.
     * @throws ValidationException
     */
    private function gastoFiltrado(Request $request, string $tipo, $imagen = false)
    {
        $gastosQuery = null;
        try {
            $fecha_inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fecha_fin = Carbon::parse($request->fecha_fin)->endOfDay();
            $request['id_usuario'] = $request['empleado'];

            switch ($request->tipo_filtro) {

                case '1': // Proyecto
                    $gastosQuery = Gasto::where('id_proyecto', $request->id_proyecto);
                    break;
                case '2': // Tarea
                    $gastosQuery = Gasto::where('id_tarea', $request->id_tarea);

                    break;
                case '3': //Detalle
                    $gastosQuery = Gasto::where('detalle', $request->detalle);
                    break;
                case '4': // subdetalle
                    if ($request->subdetalle != null)
                        $gastosQuery = Gasto::whereHas('subDetalle', function ($q) use ($request) {
                            $q->whereIn('subdetalle_gasto_id', $request->subdetalle);
                        });
                    break;
                case '5'://Autorizacion
                    $gastosQuery = Gasto::where('aut_especial', $request->aut_especial);
                    break;
                case '6': //Empleado
                    $gastosQuery = Gasto::where('id_usuario', $request->empleado);
                    break;
                case '7':// RUC
                    $gastosQuery = Gasto::where('ruc', $request->ruc);

                    break;
                case '8': // sin factura
                    $request['ruc'] = '9999999999999';
                    $gastosQuery = Gasto::where('ruc', $request->ruc);
                    break;
                case '9': //ciudad
                    $gastosQuery = Gasto::where('id_lugar', $request->id_lugar);
//                    if ($request->tipo_filtro == 9) {
//                        $gastosQuery->whereHas('empleado', function ($query) use ($request) {
//                            $query->where('canton_id', $request['ciudad']);
//                        });
//                    }
                    break;
                case '10': // grupo
                    $ids_empleados_grupo = match ($request->grupo) {
                        0 => Empleado::whereNull('grupo_id')->pluck('id'),
                        default => Empleado::where('grupo_id', $request->grupo)->pluck('id'),
                    };
                    $gastosQuery = Gasto::whereIn('id_usuario', $ids_empleados_grupo);
                    break;
                case '11': // cliente
                    $gastosQuery = Gasto::where('cliente_id', $request->cliente_id);
                    break;
                default: // todos o case '0'
                    $gastosQuery = Gasto::ignoreRequest([
                        'tipo_saldo',
                        'tipo_filtro',
                        'sub_detalle',
                        'empleado',
                        'tarea',
                        'autorizador',
                        'fecha_inicio',
                        'fecha_fin',
                        'estado',
                        'proyecto',
                        'ciudad',
                        'subdetalle'
                    ])
                        ->filter()
                        ->with(
                            'empleado',
                            'detalleEstado',
                            'subDetalle',
                            'proyecto'
                        );
            }
            // Se aplica filtro de rangos de fechas y obtener solo gastos aprobados
            $gastosQuery->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', Gasto::APROBADO);

            $gastos = $gastosQuery->get();

            $usuario = null;
            $nombre_reporte = 'reporte_gastos';
            $results = Gasto::empaquetar($gastos);
            $titulo = 'REPORTE ';
            $subtitulo = '';
            $fecha = Carbon::parse($fecha_inicio);
            $fecha_anterior = $fecha->subDay()->format('Y-m-d');
            $acreditaciones = 0;
            $transferencia = 0;
            $transferencia_recibida = 0;
            $gastos_totales = 0;
            $total = 0;
            $saldo_old = 0;
            $usuario_nombre = '';
            $usuario_canton = '';
            switch ($request->tipo_filtro) {
                case self::PROYECTO:
                    $proyecto = Proyecto::where('id', $request->id_proyecto)->first();
                    $titulo .= 'DE GASTOS POR PROYECTO ';
                    $subtitulo = 'PROYECTO: ' . $proyecto->codigo_proyecto . ' - ' . $proyecto->nombre;
                    break;
                case self::TAREA:
                    $tarea = Tarea::where('id', $request->id_tarea)->first();
                    $titulo .= 'DE GASTOS POR TAREA ';
                    $subtitulo = 'TAREA: ' . $tarea->codigo_tarea . ' - ' . $tarea->titulo;
                    break;
                case self::DETALLE:
                    $detalle = DetalleViatico::where('id', $request->detalle)->first();
                    $titulo .= 'DE GASTOS POR DETALLE ';
                    $subtitulo = 'DETALLE: ' . $detalle->descripcion;
                    break;
                case self::SUBDETALLE:
                    $sub_detalle = SubDetalleViatico::where('id', $request->subdetalle)->first();
                    $titulo .= 'DE GASTOS POR SUBDETALLE ';
                    $subtitulo = 'SUBDETALLE: ' . $sub_detalle->descripcion;
                    break;
                case self::AUTORIZADORGASTO:
                    $autorizador = Empleado::where('id', $request->aut_especial)->first();
                    $titulo .= 'DE GASTOS POR AUTORIZADOR ';
                    $subtitulo = 'AUTORIZADOR: ' . $autorizador->nombres . ' ' . $autorizador->apellidos;
                    break;
                case self::EMPLEADO:
                    $usuario = Empleado::where('id', $request->empleado)->first();
                    $usuario_nombre = $usuario->nombres . ' ' . $usuario->apellidos;
                    $usuario_canton = $usuario->canton->canton;

                    $titulo .= 'DE GASTOS POR EMPLEADO ';
                    $subtitulo = 'EMPLEADO: ' . $usuario->nombres . ' ' . $usuario->apellidos;
                    $saldo_anterior = SaldoGrupo::where('id_usuario', $request->empleado)
                        ->where('fecha', '<=', $fecha_anterior)
                        ->orderBy('created_at', 'desc')->limit(1)->first();
                    $acreditaciones = Acreditaciones::with('usuario')
                        ->where('id_usuario', $request->empleado)
                        ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                        ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                        ->sum('monto');
                    $transferencia = Transferencias::where('usuario_envia_id', $request->empleado)
                        ->where('estado', 1)
                        ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                        ->sum('monto');
                    $transferencia_recibida = Transferencias::where('usuario_recibe_id', $request->empleado)
                        ->where('estado', 1)
                        ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                        ->sum('monto');
                    $gastos = Gasto::with('empleado', 'detalleEstado', 'detalle_info', 'subDetalle', 'authEspecialUser', 'tarea')
                        ->where('estado', Gasto::APROBADO)
                        ->where('id_usuario', $request->empleado)
                        ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                        ->get();
                    $gastos_totales = $gastos->sum('total');
                    $saldo_old = $saldo_anterior != null ? $saldo_anterior->saldo_actual : 0;
                    $total = $saldo_old + $acreditaciones - $transferencia + $transferencia_recibida - $gastos_totales;
                    break;
                case self::RUC:
                    $ruc = Gasto::where('ruc', $request->ruc)->first();
                    $titulo .= 'DE GASTOS POR RUC ';
                    $subtitulo = 'RUC: ' . $ruc->ruc;
                    break;
                case self::CIUDAD:
                    $ciudad = Canton::where('id', $request['id_lugar'])->first();
                    $titulo .= 'DE GASTOS POR CIUDAD ';
                    $subtitulo = 'Ciudad: ' . $ciudad->canton;
                    break;
                case self::CLIENTE:
                    $cliente = Cliente::find($request->cliente_id);
                    $titulo .= 'DE GASTOS POR CLIENTE ';
                    $subtitulo = 'Cliente: ' . $cliente->empresa->razon_social;
                    break;
            }
            $titulo .= 'DEL ' . $fecha_inicio->format('Y-m-d') . ' AL ' . $fecha_fin->format('Y-m-d') . '.';
            $tipo_filtro = $request->tipo_filtro;
            $reportes = [
                'gastos' => $results,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'usuario' => $usuario,
                'usuario_nombre' => $usuario_nombre,
                'usuario_canton' => $usuario_canton,
                'titulo' => $titulo,
                'subtitulo' => $subtitulo,
                'tipo_filtro' => $tipo_filtro,
                'subdetalle' => $request->subdetalle,
                'fecha_anterior' => $fecha_anterior,
                'saldo_anterior' => $saldo_old,
                'acreditaciones' => $acreditaciones,
                'transferencia' => $transferencia,
                'transferencia_recibida' => $transferencia_recibida,
                'gastos_totales' => $gastos_totales,
                'total_suma' => $total,
            ];
            $vista = $imagen ? 'exports.reportes.reporte_consolidado.reporte_gastos_usuario_imagen_filtrado' : 'exports.reportes.reporte_consolidado.reporte_gastos_filtrado';
            $export_excel = new GastoFiltradoExport($reportes);
            $tamanio_papel = $imagen ? 'A2' : 'A4';
            return $this->reporteService->imprimirReporte($tipo, $tamanio_papel, 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Throwable $e) {
            Log::channel('testing')->error('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'gastoFiltrado');
        }
    }

    /**
     * @throws ValidationException
     */
    private function fotografiasOM(Request $request, string $tipo)
    {
        try {
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            if ($request->tipo_filtro == 8) {
                $request['ruc'] = '9999999999999';
            }
            $query = Gasto::whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', Gasto::APROBADO)->with(['empleado', 'detalleEstado', 'subDetalle', 'proyecto']);

            if ($request->subdetalle != null) {
                $query->whereHas('subDetalle', function ($q) use ($request) {
                    $q->whereIn('subdetalle_gasto_id', $request['subdetalle']);
                });
            }
            if ($request->nodos != null) {
                $query->whereIn('nodo_id', $request['nodos']);
            }
            $titulo = 'REPORTE';

            $gastos = $query->get();
//            Log::channel('testing')->info('Log', ['gastos', $gastos]);

            switch ($request->tipo_filtro) {
                case self::SUBDETALLE:
                    $sub_detalle = SubDetalleViatico::where('id', $request->subdetalle)->first();
                    $subtitulo = 'SUB DETALLE: ' . $sub_detalle->descripcion;
                    $titulo .= ' DE FOTOGRAFIAS';
                    break;
                default:
                    $titulo .= ' DE GASTOS POR SUBDETALLE';
                    $subtitulo = '';
                    break;
            }

            $titulo .= ' DEL ' . $fecha_inicio . ' AL ' . $fecha_fin . '.';
            $configuracion = ConfiguracionGeneral::first();
            if ($tipo == 'excel')
                throw new Exception('Este reporte no puede imprimirse en EXCEL. Intentalo en PDF');
            $pdf = Pdf::loadView('exports.reportes.reporte_consolidado.reporte_fotografias_om', [
                'configuracion' => $configuracion,
//                'gastos' => $gastos,
                'gastos' => $this->agruparPorCiudadOM($this->mapearGastosOM($gastos)),
                'titulo' => $titulo,
                'subtitulo' => $subtitulo,
            ]);
            $pdf->render();
            return $pdf->output();
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e, 'Fotografías OyM');
        }
    }

    private function mapearGastosOM(Collection $gastos)
    {
        $results = [];
        foreach ($gastos as $gasto) {
            $row['empleado'] = Empleado::extraerNombresApellidos($gasto->empleado);
            $row['grupo'] = $gasto->empleado->grupo?->nombre;
            $row['nodo'] = $gasto->nodo?->nombre;
            $row['ruc'] = $gasto->ruc;
            $row['fecha_viat'] = $gasto->fecha_viat;
            $row['factura'] = $gasto->factura;
            $row['canton'] = $gasto->canton->canton;
            $row['comprobante'] = $gasto->comprobante;
            $row['comprobante2'] = $gasto->comprobante2;
            $results[] = $row;
        }
        return $results;
    }

    private function agruparPorCiudadOM(array $datos)
    {
        $collection = collect($datos);
        return $collection->groupBy('canton')->map(function ($item) {
            return $item->groupBy('grupo');
        });
    }

    /**
     * It's a function that receives two parameters, one of them is a request object and the other is a
     * string
     *
     * @param Request $request The request object.
     * @param string $tipo The type of report you want to generate.
     * @throws ValidationException
     */
    private function acreditacion(Request $request, string $tipo)
    {
        try {
            $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
            $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
            $usuario = null;
            if ($request->empleado == null || $request->empleado == 0) {
                $acreditaciones = Acreditaciones::with('usuario')
                    ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
                // Calcular la suma de los montos.
            } else {
                $acreditaciones = Acreditaciones::with('usuario')
                    ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                    ->where('id_usuario', $request->empleado)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
                $usuario = Empleado::where('id', $request->empleado)
                    ->first();
            }
            if ($request->grupo) {
                $ids_empleados_grupo = match ($request->grupo) {
                    0 => Empleado::whereNull('grupo_id')->pluck('id'),
                    default => Empleado::where('grupo_id', $request->grupo)->pluck('id'),
                };
                $acreditaciones = Acreditaciones::whereIn('id_usuario', $ids_empleados_grupo)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
            }
            $sumaMontos = $acreditaciones->sum('monto');
            $nombre_reporte = 'reporte_saldoActual';
            $results = Acreditaciones::empaquetar($acreditaciones);
            $reportes = ['acreditaciones' => $results, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'usuario' => $usuario, 'total' => $sumaMontos];
            $vista = 'exports.reportes.reporte_consolidado.reporte_acreditaciones_usuario';
            $export_excel = new AcreditacionesExport($reportes);
            return $this->reporteService->imprimirReporte($tipo, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error acreditacion', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Esta funcion devuelve los reportes de gastos con imagenes de un empleado.
     * Utiliza la plantilla resources\views\exports\reportes\reporte_consolidado\reporte_gastos_usuario_imagen.blade.php
     *
     * @param Request $request The request object.
     * @param string $tipo The type of report you want to generate.
     * @throws ValidationException
     */
    private function gasto(Request $request, string $tipo, $imagen = false)
    {
        try {
            $fecha_inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fecha_fin = Carbon::parse($request->fecha_fin)->endOfDay();
            $ultimo_saldo = 0;
            $acreditaciones = 0;
            $gastos_totales = 0;
            $transferencia = 0;
            $transferencia_recibida = 0;
            $transferencias_enviadas = [];
            $transferencias_recibidas = [];
            $sumatoria_aprobados_fuera_mes = 0;
            $ajuste_saldo_ingreso = 0;
            $ajuste_saldo_ingreso_reporte = [];
            $ajuste_saldo_egreso = 0;
            $ajuste_saldo_egreso_reporte = [];
            $registros_fuera_mes_suman = collect();
            $registros_fuera_mes_restan = collect();
            $total = 0;
            $usuario_canton = '';
            $fecha_anterior = '';
            $saldo_anterior = 0;
            if ($request->empleado == null) {
                $gastos = Gasto::with('empleado', 'detalleEstado', 'subDetalle', 'authEspecialUser', 'tarea')
                    ->where('estado', Gasto::APROBADO)
                    ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                    ->get();
                $usuario = '';
                $empleado = new Empleado();
            } else {
                $fecha = Carbon::parse($fecha_inicio);
                $fecha_anterior = $fecha->subDay()->format('Y-m-d');
                $saldo_anterior = SaldoService::obtenerSaldoAnterior($request->empleado, $fecha_anterior, $fecha_inicio);
// SaldoGrupo::where('id_usuario', $request->empleado)
//                    ->where('fecha', '<=', $fecha_anterior)
//                    ->orderBy('created_at', 'desc')->limit(1)->first();
                if ($saldo_anterior != null) {
                    $fecha_anterior = $saldo_anterior->fecha;
                }
                $ultimo_saldo = SaldoGrupo::where('id_usuario', $request->empleado)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->orderBy('id', 'desc')
                    ->first();
                $acreditaciones = Acreditaciones::with('usuario')
                    ->where('id_usuario', $request->empleado)
                    ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->sum('monto');

                $gastos = Gasto::with('empleado', 'detalleEstado', 'detalle_info', 'subDetalle', 'authEspecialUser', 'tarea')
                    ->where('estado', Gasto::APROBADO)
                    ->where('id_usuario', $request->empleado)
                    ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                    ->get();
                $gastos_totales = $gastos->sum('total');
                $transferencias_enviadas = $this->saldoService->obtenerTransferencias($request->empleado, $fecha_inicio, $fecha_fin);
                $transferencia = $transferencias_enviadas->sum('monto');
                $transferencias_recibidas = $this->saldoService->obtenerTransferencias($request->empleado, $fecha_inicio, $fecha_fin, false);
                $transferencia_recibida = $transferencias_recibidas->sum('monto');
                $ajuste_saldo_ingreso_reporte = $this->saldoService->obtenerAjustesSaldos($fecha_inicio, $fecha_fin, $request->empleado);
                $ajuste_saldo_ingreso = $ajuste_saldo_ingreso_reporte->sum('monto');
                $ajuste_saldo_ingreso_reporte = AjusteSaldoFondoRotativo::empaquetar($ajuste_saldo_ingreso_reporte);

                $ajuste_saldo_egreso_reporte = $this->saldoService->obtenerAjustesSaldos($fecha_inicio, $fecha_fin, $request->empleado, AjusteSaldoFondoRotativo::EGRESO);
                $ajuste_saldo_egreso = $ajuste_saldo_egreso_reporte->sum('monto');
                $ajuste_saldo_egreso_reporte = AjusteSaldoFondoRotativo::empaquetar($ajuste_saldo_egreso_reporte);

                $saldo_old = $saldo_anterior != null ? $saldo_anterior->saldo_actual : 0;
                $registros_fuera_mes_restan = $this->saldoService->obtenerRegistrosFueraMes($request->empleado, $fecha_inicio, $fecha_fin, false);
                $sumatoria_fuera_mes_restan = $registros_fuera_mes_restan->sum('saldo_depositado');
                $registros_fuera_mes_suman = $this->saldoService->obtenerRegistrosFueraMes($request->empleado, $fecha_inicio, $fecha_fin);
                $sumatoria_fuera_mes_suman = $registros_fuera_mes_suman->sum('saldo_depositado');
                $sumatoria_aprobados_fuera_mes = $sumatoria_fuera_mes_restan - $sumatoria_fuera_mes_suman;
                $total = ($saldo_old + $acreditaciones - $transferencia + $transferencia_recibida - $gastos_totales) + $ajuste_saldo_ingreso - $ajuste_saldo_egreso - $sumatoria_aprobados_fuera_mes;
                $empleado = Empleado::where('id', $request->empleado)->first();
                $usuario = $empleado->nombres . '' . ' ' . $empleado->apellidos;
                $usuario_canton = $empleado->canton->canton;
            }
            $nombre_reporte = 'reporte_gastos';
            $results = Gasto::empaquetar($gastos);
            $reportes = [
                'gastos' => $results,
                'fecha_inicio' => $fecha_inicio->format('d-m-Y'),
                'fecha_fin' => $fecha_fin->format('d-m-Y'),
                'fecha_anterior' => $fecha_anterior,
                'usuario' => $usuario,
                'empleado' => $empleado,
                'usuario_canton' => $usuario_canton,
                'saldo_anterior' => $saldo_anterior != null ? $saldo_anterior->saldo_actual - $sumatoria_aprobados_fuera_mes : 0,
                'gastos_aprobados_fuera_mes' => $sumatoria_aprobados_fuera_mes,
//                'registros_fuera_mes' => $registros_fuera_mes_suman->merge($registros_fuera_mes_restan),
                'registros_fuera_mes' => $this->saldoService->obtenerRegistrosFueraMesFuturo($request->empleado, $fecha_inicio, $fecha_fin),
                'ultimo_saldo' => $ultimo_saldo,
                'total_suma' => $total,
                'acreditaciones' => $acreditaciones,
                'gastos_totales' => $gastos_totales,
                'transferencia' => $transferencia,
                'transferencia_recibida' => $transferencia_recibida,
                'transferencias_enviadas' => $transferencias_enviadas,
                'transferencias_recibidas' => $transferencias_recibidas,
                'ajuste_saldo_ingreso' => $ajuste_saldo_ingreso,
                'ajuste_saldo_ingreso_reporte' => $ajuste_saldo_ingreso_reporte,
                'ajuste_saldo_egreso' => $ajuste_saldo_egreso,
                'ajuste_saldo_egreso_reporte' => $ajuste_saldo_egreso_reporte,
            ];
//            Log::channel('testing')->info('Log', ['gasto con imagen?', $imagen, $request->all(), $reportes]);
            $vista = $imagen ? 'exports.reportes.reporte_consolidado.reporte_gastos_usuario_imagen' : 'exports.reportes.reporte_consolidado.reporte_gastos_usuario';
            // Log::channel('testing')->info('Log', ['gastos con imagen', count($reportes['gastos']), $reportes]);
            $export_excel = new GastoConsolidadoExport($reportes);
            $tamanio_papel = $imagen ? 'A2' : 'A4';
            return $this->reporteService->imprimirReporte($tipo, $tamanio_papel, 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'SaldoController::gasto');
        }
    }

    /**
     * La función `reporteEstadoCuenta` genera un informe consolidado de transacciones financieras para un
     * usuario específico dentro de un rango de fechas determinado.
     *
     * @param Request $request La función `reporteEstadoCuenta` parece generar un informe basado en los
     * datos de la solicitud proporcionados y un tipo de informe. Calcula diversos datos financieros, como
     * saldos, transacciones y gastos de un usuario determinado dentro de un rango de fechas específico.
     * @param string $tipo_reporte Tipo_reporte es un parámetro de cadena que especifica el tipo de informe
     * a generar. Podría ser algo como "PDF" o "Excel".
     *
     * @return Response|BinaryFileResponse función `reporteEstadoCuenta` está devolviendo el resultado de llamar al método
     * `imprimir_reporte` del objeto ``. El método `imprimir_reporte` parece generar e
     * imprimir un informe basado en los datos y parámetros proporcionados. El valor de retorno del método
     * `imprimir_reporte` es lo que finalmente devuelve el método `reporteEstadoCuenta
     * @throws Exception|Throwable
     */
    private function reporteEstadoCuenta(Request $request, string $tipo_reporte)
    {
        try {
            if ($request->fecha_inicio > $request->fecha_fin) throw new Exception('La fecha inicial no puede ser superior a la fecha final');
            $fecha_inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fecha_fin = Carbon::parse($request->fecha_fin)->endOfDay();

            $fecha = Carbon::parse($fecha_inicio);
            $fecha_anterior = $fecha->subDay()->format('Y-m-d'); // el día anterior a la fecha de inicio
//            $fecha_fin_aux = Carbon::parse($fecha_fin)->addDays(7)->format('Y-m-d'); // se le aumenta 7 días a la fecha final
            $saldo_anterior = SaldoService::obtenerSaldoAnterior($request->empleado, $fecha_anterior, $fecha_inicio);
            $fecha_anterior = $fecha->format('Y-m-d');

            $es_nuevo_saldo = SaldoService::existeSaldoNuevaTabla($request->empleado);
            // Log::channel('testing')->info('Log', ['es nuevo saldo', $fecha_anterior, $es_nuevo_saldo, $fecha_inicio, $request->empleado]);
            //Gastos
            $gastos = Gasto::with('empleado', 'detalle_info', 'subDetalle', 'authEspecialUser')
                ->selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('id_usuario', '=', $request->empleado)
                ->where(function ($query) {
                    $query->where('estado', '=', Gasto::APROBADO)
                        ->orWhere('estado', '=', Gasto::ANULADO);
                })
                ->get();
            $gastos = SaldoGrupo::verificarGastosRepetidosEnSaldoGrupo($gastos);
            //Transferencias
            $transferencias_enviadas = $this->saldoService->obtenerTransferencias($request->empleado, $fecha_inicio, $fecha_fin, true, true);
            $transferencias_recibidas = $this->saldoService->obtenerTransferencias($request->empleado, $fecha_inicio, $fecha_fin, false, true);
            //Acreditaciones
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $request->empleado)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $ajuste_saldo = AjusteSaldoFondoRotativo::where('created_at', '>=', Carbon::parse($fecha_inicio)->endOfDay())
                ->where('destinatario_id', $request->empleado)
                ->where('created_at', '<=', Carbon::parse($fecha_fin)->endOfDay())
                ->get();
            $ultimo_saldo = SaldoService::obtenerSaldoActualUltimaFecha($fecha_fin, $request->empleado);
            $estado_cuenta_anterior = $request->fecha_inicio != '01-06-2023' ? $this->saldoService->EstadoCuentaAnterior($request->fecha_inicio, $request->empleado) : $saldo_anterior->saldo_actual;
            $saldo_anterior_db = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
            $salt_ant = $estado_cuenta_anterior !== 0 ? $estado_cuenta_anterior : $saldo_anterior_db;
            $nuevo_elemento = [
                'item' => 1,
                'fecha' => $fecha_anterior,
                'fecha_creacion' => $saldo_anterior == null ? $fecha : $saldo_anterior->created_at,
                'num_comprobante' => '',
                'descripcion' => 'SALDO ANTERIOR',
                'observacion' => '',
                'ingreso' => 0,
                'gasto' => 0,
                'saldo_anterior' => $salt_ant,
                'saldo' => $saldo_anterior_db,
            ];
            //Unir todos los reportes

//            Log::channel('testing')->info('Log', ['fechas', $fecha_inicio, $fecha_fin]);
            $saldos_fondos = Saldo::with('saldoable')->where('empleado_id', $request->empleado)
                ->where(function ($query) use ($fecha_inicio, $fecha_fin) {
                    $query->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
                        ->orWhereBetween('fecha', [$fecha_inicio, $fecha_fin]);
                })
                ->get();
            $reportes_unidos_historico = $gastos->merge($transferencias_enviadas)->merge($transferencias_recibidas)->merge($acreditaciones)->merge($ajuste_saldo);
            $reportes_unidos = $es_nuevo_saldo
                ? Saldo::empaquetarCombinado($nuevo_elemento, $saldos_fondos, $request->empleado, $fecha_inicio, $fecha_fin)
                : SaldoGrupo::empaquetarCombinado($nuevo_elemento, $reportes_unidos_historico, $request->empleado);
            $sub_total = 0;
            $nuevo_saldo = $ultimo_saldo != null ? $ultimo_saldo->saldo_actual : 0;
            $empleado = Empleado::where('id', $request->empleado)->first();
            $usuario = User::where('id', $empleado->usuario_id)->first();
            $nombre_reporte = 'reporte_estado_cuenta';

            $reportes = [
                'fecha_anterior' => $fecha_anterior,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'empleado' => $empleado,
                'usuario' => $usuario,
                'saldo_anterior' => $saldo_anterior_db,
                'reportes_unidos' => $reportes_unidos,
                'nuevo_saldo' => $nuevo_saldo,
                'sub_total' => $sub_total,
            ];

            $vista = 'exports.reportes.reporte_consolidado.reporte_movimiento_saldo';
            $export_excel = new EstadoCuentaExport($reportes);
            return $this->reporteService->imprimirReporte($tipo_reporte, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Throwable $e) {
            Log::channel('testing')->info('Log', ['error reporteEstadoCuenta', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }

    /**
     * La función "reporte_consolidado" genera un informe consolidado basado en la solicitud y el tipo
     * dado.
     *
     * @param Request $request El parámetro `` es un objeto que contiene los datos enviados en la
     * solicitud HTTP. Se utiliza para recuperar los valores de las propiedades `fecha_inicio` y
     * `fecha_fin`.
     * @param string $tipo El parámetro "tipo" se utiliza para determinar el tipo de informe a generar.
     * Probablemente, sea un valor de cadena que especifica el formato del informe, como "pdf" o "excel".
     *
     * @return BinaryFileResponse|Response
     * @throws ValidationException
     */
    private function reporteConsolidado(Request $request, string $tipo)
    {
        try {
            $fecha_inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fecha_fin = Carbon::parse($request->fecha_fin)->endOfDay();
            $fecha = Carbon::parse($fecha_inicio);
            $fecha_anterior = $fecha->subDay()->format('Y-m-d');
            $saldo_anterior = SaldoService::obtenerSaldoAnterior($request->empleado, $fecha_anterior, $fecha_inicio);
            if ($saldo_anterior != null) {
                $fecha = Carbon::parse($saldo_anterior->fecha);
                $fecha_anterior = $fecha->format('Y-m-d');
            }
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $request->empleado)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $gastos_reporte = Gasto::with('empleado', 'detalle_info', 'subDetalle', 'authEspecialUser', 'tarea')->selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', '=', 1)
                ->where('id_usuario', '=', $request->empleado)
                ->get();
            $gastos = $gastos_reporte->sum('total');
            $registros_fuera_mes_restan = $this->saldoService->obtenerRegistrosFueraMes($request->empleado, $fecha_inicio, $fecha_fin, false);
//            Log::channel('testing')->info('Log', ['gastos que restan', ]);
            $sumatoria_fuera_mes_restan = $registros_fuera_mes_restan->sum('saldo_depositado');
            $registros_fuera_mes_suman = $this->saldoService->obtenerRegistrosFueraMes($request->empleado, $fecha_inicio, $fecha_fin);
//            Log::channel('testing')->info('Log', ['gastos que suman', ]);
            $sumatoria_fuera_mes_suman = $registros_fuera_mes_suman->sum('saldo_depositado');
            /* se procede a cambiar para que la sumatoria sea fuera_mes_suman - fuera_mes_restan
            este cambio se produce porque en un reporte sumaba el ultimo saldo-$sumatoria_aprobados_fuera_mes (125 - (-75))
            lo cual daba un valor positivo de 200, cuando lo correcto era (125-75=50) */
            $sumatoria_aprobados_fuera_mes = $sumatoria_fuera_mes_restan - $sumatoria_fuera_mes_suman;
//            $sumatoria_aprobados_fuera_mes = $sumatoria_fuera_mes_suman - $sumatoria_fuera_mes_restan;
            $gastos_reporte = Gasto::empaquetar($gastos_reporte);
            $transferencias_enviadas = Transferencias::where('usuario_envia_id', $request->empleado)
                ->with('empleadoRecibe', 'empleadoEnvia')
                ->where('estado', Transferencias::APROBADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencia = $transferencias_enviadas->sum('monto');
            $transferencias_recibidas = Transferencias::where('usuario_recibe_id', $request->empleado)
                ->with('empleadoRecibe', 'empleadoEnvia')
                ->where('estado', Transferencias::APROBADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencia_recibida = $transferencias_recibidas->sum('monto');
//            $ajuste_saldo_ingreso_reporte = AjusteSaldoFondoRotativo::whereBetween(DB::raw('DATE(created_at)'), [$fecha_inicio, $fecha_fin])
//                ->where('destinatario_id', $request->empleado)
//                ->where('tipo', AjusteSaldoFondoRotativo::INGRESO)
//                ->get();
            $ajuste_saldo_ingreso_reporte = $this->saldoService->obtenerAjustesSaldos($fecha_inicio, $fecha_fin, $request->empleado);
            $ajuste_saldo_ingreso = $ajuste_saldo_ingreso_reporte->sum('monto');
            $ajuste_saldo_ingreso_reporte = AjusteSaldoFondoRotativo::empaquetar($ajuste_saldo_ingreso_reporte);
//            $ajuste_saldo_egreso_reporte = AjusteSaldoFondoRotativo::whereBetween(DB::raw('DATE(created_at)'), [$fecha_inicio, $fecha_fin])
//                ->where('destinatario_id', $request->empleado)
//                ->where('tipo', AjusteSaldoFondoRotativo::EGRESO)
//                ->get();
            $ajuste_saldo_egreso_reporte = $this->saldoService->obtenerAjustesSaldos($fecha_inicio, $fecha_fin, $request->empleado, AjusteSaldoFondoRotativo::EGRESO);
            $ajuste_saldo_egreso = $ajuste_saldo_egreso_reporte->sum('monto');
            $ajuste_saldo_egreso_reporte = AjusteSaldoFondoRotativo::empaquetar($ajuste_saldo_egreso_reporte);

            $ultimo_saldo = SaldoService::obtenerSaldoEmpleadoEntreFechas($fecha_inicio, $fecha_fin, $request->empleado);
            $sub_total = 0;
            $nuevo_saldo = $ultimo_saldo != null ? $ultimo_saldo->saldo_actual : 0;
            $saldo_old = $saldo_anterior != null ? $saldo_anterior->saldo_actual : 0;
            $total = ($saldo_old + $acreditaciones - $transferencia + $transferencia_recibida - $gastos) + $ajuste_saldo_ingreso - $ajuste_saldo_egreso - $sumatoria_aprobados_fuera_mes;
            $empleado = Empleado::where('id', $request->empleado)->first();

            $usuario = User::where('id', $empleado->usuario_id)->first();
            $nombre_reporte = 'reporte_consolidado';
            $reportes = [
                'fecha_anterior' => $fecha_anterior,
                'fecha_inicio' => $fecha_inicio->format('d-m-Y'),
                'fecha_fin' => $fecha_fin->format('d-m-Y'),
                'empleado' => $empleado,
                'usuario' => $usuario,
                'saldo_anterior' => $saldo_anterior != null ? $saldo_anterior->saldo_actual - $sumatoria_aprobados_fuera_mes : 0,
                'acreditaciones' => $acreditaciones,
                'gastos' => $gastos,
                'gastos_reporte' => $gastos_reporte,
                'gastos_aprobados_fuera_mes' => $sumatoria_aprobados_fuera_mes,
                'transferencia' => $transferencia,
                'transferencia_recibida' => $transferencia_recibida,
                'transferencias_enviadas' => $transferencias_enviadas,
                'transferencias_recibidas' => $transferencias_recibidas,
                'ajuste_saldo_ingreso' => $ajuste_saldo_ingreso,
                'ajuste_saldo_ingreso_reporte' => $ajuste_saldo_ingreso_reporte,
                'ajuste_saldo_egreso' => $ajuste_saldo_egreso,
                'ajuste_saldo_egreso_reporte' => $ajuste_saldo_egreso_reporte,
//                'registros_fuera_mes' => $registros_fuera_mes_suman->merge($registros_fuera_mes_restan),
                'registros_fuera_mes' => $this->saldoService->obtenerRegistrosFueraMesFuturo($request->empleado, $fecha_inicio, $fecha_fin),
                'nuevo_saldo' => $nuevo_saldo,
                'sub_total' => $sub_total,
                'total_suma' => $total
            ];
//            Log::channel('testing')->info('Log', ['saldos',
//                $saldo_anterior,
//                $sumatoria_fuera_mes_restan,
//                $sumatoria_fuera_mes_suman,
//                $sumatoria_aprobados_fuera_mes]);
            $vista = 'exports.reportes.reporte_consolidado.reporte_consolidado_usuario';
            $export_excel = new ConsolidadoExport($reportes);
            return $this->reporteService->imprimirReporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['error consolidado', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'encontrado en reporteConsolidado');
        }
    }

    /**
     * La función `reporte_transferencia` genera un informe sobre transacciones de transferencia en función
     * de los parámetros de solicitud proporcionados.
     *
     * @param Request $request
     * @param string $tipo
     * @return BinaryFileResponse|Response función `reporte_transferencia` está devolviendo el resultado de llamar al método
     * `imprimir_reporte` del objeto `reporteService`. Los parámetros pasados a `imprimir_reporte` son
     * ``, `'A4'`, `'portail'`, ``, ``, `` y ``.
     * @throws ValidationException
     */
    private function reporteTransferencia(Request $request, string $tipo)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $fecha = Carbon::parse($fecha_inicio);
            $fecha_anterior = $fecha->subDay()->format('Y-m-d');
            $transferencias = Transferencias::where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencia_total = $transferencias->sum('monto');
            $empleado = null;
            $usuario = null;
            $nombre_reporte = 'reporte_transferencia_saldo';
            $transferencias_enviadas = null;
            $transferencias_recibidas = null;
            $transferencia_enviada = 0;
            $transferencia_recibida = 0;
            if ($request->empleado != null) {
                $empleado = Empleado::where('id', $request->empleado)->first();
                $usuario = User::where('id', $empleado->usuario_id)->first();
                $transferencias_enviadas = $this->saldoService->obtenerTransferencias($request->empleado, $fecha_inicio, $fecha_fin);
                $transferencia_enviada = $transferencias_enviadas->sum('monto');
                $transferencias_recibidas = $this->saldoService->obtenerTransferencias($request->empleado, $fecha_inicio, $fecha_fin, false);
                $transferencia_recibida = $transferencias_recibidas->sum('monto');
            }

            $reportes = [
                'fecha_anterior' => $fecha_anterior,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'empleado' => $empleado,
                'usuario' => $usuario,
                'transferencias' => $transferencias,
                'transferencia_enviada' => $transferencia_enviada,
                'transferencia_total' => $transferencia_total,
                'transferencias_enviadas' => $transferencias_enviadas,
                'transferencias_recibidas' => $transferencias_recibidas,
                'transferencia_recibida' => $transferencia_recibida,

            ];
            $vista = 'exports.reportes.reporte_consolidado.reporte_transferencia_saldo';
            $export_excel = new TranferenciaSaldoExport($reportes);
            return $this->reporteService->imprimirReporte($tipo, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'encontrado en reporteTransferencia');
        }
    }


    /**
     * La función `gastocontabilidad` recupera gastos dentro de un rango de fechas específico para un
     * usuario determinado y devuelve los resultados en formato JSON.
     *
     * @param Request $request La función `gastocontabilidad` toma como parámetro un objeto `Request`. Este
     * objeto `Request` probablemente contenga datos enviados por el cliente, como `fecha_inicio` (fecha de
     * inicio) y `fecha_fin` (fecha de finalización) para filtrar gastos.
     *
     * @return JsonResponse función `gastocontabilidad` está devolviendo una respuesta JSON que contiene los
     * resultados de la consulta de gastos (`) dentro de un rango de fechas específico. Los gastos
     * se recuperan de la base de datos con base en el ID de usuario (->usuario`) y el rango de
     * fechas especificado por los parámetros `fecha_inicio` y `fecha_fin`. Luego, los gastos se formatean
     * utilizando `GastoResource`
     * @throws ValidationException
     */
    public function gastoContabilidad(Request $request)
    {
        try {
            $mask = 'Y-m-d';
            $date_inicio = Carbon::createFromFormat($mask, $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat($mask, $request->fecha_fin);
            $fecha_inicio = $date_inicio->format($mask);
            $fecha_fin = $date_fin->format($mask);
            $gastos = Gasto::with('empleado', 'detalleEstado', 'subDetalle')
                ->where('id_usuario', $request->usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->orderBy('fecha_viat')
                ->get();
            $results = GastoResource::collection($gastos);
            return response()->json(compact('results'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'encontrado en gastoContabilidad');
        }
    }
}
