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
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\FondosRotativos\SaldoService;
use Src\Shared\Utils;

class SaldoController extends Controller
{
    private $entidad = 'saldo';
    private $reporteService;
    private $saldoService;
    private const ACREDITACION = 1;
    private const GASTO = 2;
    private const CONSOLIDADO = 3;
    private const ESTADO_CUENTA = 4;
    private const TRANSFERENCIA = 5;
    private const GASTO_IMAGEN = 6;
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
     * @param Request request The request object.
     *
     * @return A collection of SaldoGrupoResource
     */
    public function index(Request $request)
    {
        $results = [];
        $results = Saldo::with('usuario')->ignoreRequest(['campos'])->filter()->get();
        $results = SaldoResource::collection($results);
        return response()->json(compact('results'));
    }
    /**
     * It returns a JSON response with a single SaldoGrupo model
     *
     * @param id The id of the SaldoGrupo you want to show.
     *
     * @return A JSON object with the data from the database.
     */
    public function show($id)
    {
        $SaldoGrupo = Saldo::where('id', $id)->first();
        $modelo = new SaldoResource($SaldoGrupo);
        return response()->json(compact('modelo'), 200);
    }
    /**
     * It takes the date of the current day, and then it calculates the start and end date of the week.
     * </code>
     *
     * @param Request request ->fecha
     *
     * @return <code>{
     *     "mensaje": "Registro creado correctamente",
     *     "modelo": {
     *         "id": 1,
     *         "id_usuario": 1,
     *         "saldo_anterior": 0,
     *         "saldo_actual": 0,
     *         "fecha": "2019-
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
        $datos_usuario_add_saldo = User::where('id', $request->usuario)->first();
        $datos_saldo_inicio_sem = SaldoGrupo::where('id_usuario', $request->usuario)->orderBy('id', 'desc')->first();
        $user = Auth::user();
        $fechaIni = date("Y-m-d", strtotime($request->fecha . "-$rest days"));
        $fechaFin = date("Y-m-d", strtotime($request->fecha . "+$sum days"));
        //Adaptacion de campos
        $datos = $request->all();
        $datos['id_usuario'] = $request->usuario;
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
     * It deletes a record from the database
     *
     * @param SaldoGrupo SaldoGrupo The model
     *
     * @return The response is a JSON object with the message.
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
     * @param id The id of the user
     *
     * @return The last record of the table SaldoGrupo where the id_usuario is equal to the id passed as
     * a parameter.
     */
    public function saldoActualUsuario($id)
    {
        $saldo_actual = Saldo::where('empleado_id', $id)->orderBy('id', 'desc')->first();
        $saldo_actual = $saldo_actual != null ? $saldo_actual->saldo_actual : 0;

        return response()->json(compact('saldo_actual'));
    }
    /**
     * It's a function that returns a PDF or Excel file depending on the parameter that is passed to it
     *
     * @param Request request The request object.
     * @param tipo is the type of report, it can be excel or pdf
     *
     * @return a file, but the file is not being downloaded.
     */
    public function saldoActual(Request $request, $tipo)
    {
        try {
            $usuario = Auth::user();
            $id = $request->usuario != null ?  $request->usuario : 0;
            if ($usuario->hasRole([User::ROL_COORDINADOR, User::ROL_CONTABILIDAD, User::ROL_ADMINISTRADOR])) {
                $saldos_actual_user = $request->usuario == null ?
                    Saldo::with('empleado')->whereIn('id', function ($sub) {
                        $sub->selectRaw('max(id)')->from('fr_saldos')->groupBy('empleado_id');
                    })->get()
                    : Saldo::with('empleado')->where('empleado_id', $id)->orderBy('id', 'desc')->first();
            } else {
                $empleados = Empleado::where('jefe_id', $usuario->empleado->id)->get('id', 'nombres', 'apellidos')->pluck('id');
                $saldos_actual_user = $request->usuario == null ?
                    Saldo::with('empleado')->whereIn('id', function ($sub) {
                        $sub->selectRaw('max(id)')->from('fr_saldos')->groupBy('empleado_id');
                    })->whereIn('empleado_id', $empleados)
                    ->get()
                    : Saldo::with('usuario')->where('empleado_id', $id)->orderBy('id', 'desc')->first();
            }
            $tipo_reporte = $request->usuario != null ? 'usuario' : 'todos';
            $results = Saldo::empaquetarListado($saldos_actual_user, $tipo_reporte);
            $nombre_reporte = 'reporte_saldoActual';
            $reportes =  ['saldos' => $results];
            $vista = 'exports.reportes.reporte_saldo_actual';
            $export_excel = new SaldoActualExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }


    /**
     * La función "consolidado" en PHP procesa diferentes tipos de informes según el tipo de saldo
     * solicitado.
     *
     * @param Request request La función `consolidado` en el fragmento de código que proporcionaste
     * toma dos parámetros: `` y ``.
     * @param tipo_reporte La función `consolidado`  maneja diferentes
     * tipos de reportes los cuales pueden ser PDF y Excel
     *
     * @return La función `consolidado` devuelve el resultado de una de las siguientes funciones según
     * el valor de `tipo_saldo`:
     * - Función `acreditación` si el valor es `ACREDITACIÓN`
     * - Función `gasto` si el valor es `GASTO` o `GASTO_IMAGEN`
     * - Función `reporteConsolidado` si el valor es `CONSOLIDADO`
     * - Función `reporteTransferencia` si el valor es `TRANSFERENCIA`
     */
    public function consolidado(Request $request, $tipo_reporte)
    {
        try {
            switch ($request->tipo_saldo) {
                case self::ACREDITACION:
                    return $this->acreditacion($request, $tipo_reporte);
                    break;
                case self::GASTO:
                    return $this->gasto($request, $tipo_reporte);
                    break;
                case self::CONSOLIDADO:
                    return $this->reporteConsolidado($request, $tipo_reporte);
                    break;
                case self::ESTADO_CUENTA:
                    return $this->reporteEstadoCuenta($request, $tipo_reporte);
                    break;
                case self::TRANSFERENCIA:
                    return $this->reporteTransferencia($request, $tipo_reporte);
                    break;
                case self::GASTO_IMAGEN:
                    return $this->gasto($request, $tipo_reporte, true);
                    break;
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }

    /**
     * La función `consolidadoFiltrado` en PHP toma una solicitud y un tipo de informe, luego, según el
     * tipo de saldo solicitado, llama a diferentes métodos para generar y devolver informes específicos.
     *
     * @param Request request La función `consolidadoFiltrado` toma dos parámetros: `` de tipo
     * `Request` y ``.
     * @param tipo_reporte La función `consolidadoFiltrado` toma dos parámetros: `` de tipo
     * `Request` y `` de tipo no especificado. Luego, la función usa una declaración de cambio
     * para determinar la acción en función del valor de `->tipo_saldo`. Dependiendo del valor,
     *
     * @return El método `consolidadoFiltrado` está devolviendo el resultado de uno de los siguientes
     * métodos basado en el valor de `->tipo_saldo`:
     * - `acreditacion(, )` si el valor es `self::ACREDITACION`
     * - `gastoFiltrado(, )` si el valor es `self::GASTO
     * - `reporteConsolidado(, )` si el valor es `self::CONSOLIDADO
     * - `reporteEstadoCuenta(, )` si el valor es `self::ESTADO_CUENTA
     */
    public function consolidadoFiltrado(Request $request,  $tipo_reporte)
    {
        try {
            switch ($request->tipo_saldo) {
                case self::ACREDITACION:
                    return $this->acreditacion($request,  $tipo_reporte);
                    break;
                case self::GASTO:
                    return $this->gastoFiltrado($request,  $tipo_reporte);
                    break;
                case self::CONSOLIDADO:
                    return $this->reporteConsolidado($request,  $tipo_reporte);
                    break;
                case self::ESTADO_CUENTA:
                    return $this->reporteEstadoCuenta($request,  $tipo_reporte);
                    break;
                case self::TRANSFERENCIA:
                    return $this->reporteTransferencia($request, $tipo_reporte);
                    break;
                case self::GASTO_IMAGEN:
                    return $this->gastoFiltrado($request, $tipo_reporte, true);
                    break;
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * It's a function that receives a request, a type, and returns a report
     *
     * @param Request request The request object.
     * @param tipo The type of report you want to generate, in this case it is pdf.
     */
    private function gastoFiltrado(Request $request, $tipo, $imagen = false)
    {
        try {
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $request['id_proyecto'] =  $request['proyecto'];
            $request['sub_detalle'] = $request['id_sub_detalle'];
            $request['id_usuario'] = $request['empleado'];
            $request['id_estado'] = $request['estado'];
            $request['id_tarea'] = $request['tarea'];
            $request['aut_especial'] = $request['autorizador'];
            $request['ciudad'] = $request['ciudad'];

            if ($request->tipo_filtro == 8) {
                $request['ruc'] = '9999999999999';
            }

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
                ->filter($request->all())
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', Gasto::APROBADO)
                ->with(
                    'empleado',
                    'detalleEstado',
                    'subDetalle',
                    'proyecto'
                );

            if ($request->tipo_filtro == 9) {
                $gastosQuery->whereHas('empleado', function ($query) use ($request) {
                    $query->where('canton_id', $request['ciudad']);
                });
            }

            if ($request->subdetalle != null) {
                $gastos = $gastosQuery->whereHas('subDetalle', function ($q) use ($request) {
                    $q->where('subdetalle_gasto_id', $request['subdetalle']);
                })->get();
            } else {
                $gastos = $gastosQuery->get();
            }


            $usuario = Empleado::where('id', $request->usuario)->first();
            $nombre_reporte = 'reporte_gastos';
            $results = Gasto::empaquetar($gastos);
            $titulo = 'REPORTE ';
            $subtitulo = '';
            switch ($request->tipo_filtro) {
                case '1':
                    $proyecto = Proyecto::where('id', $request->proyecto)->first();
                    $titulo .= 'DE GASTOS POR PROYECTO ';
                    $subtitulo = 'PROYECTO: ' . $proyecto->codigo_proyecto . ' - ' . $proyecto->nombre;
                    break;
                case '2':
                    $tarea = Tarea::where('id', $request->tarea)->first();
                    $titulo .= 'DE GASTOS POR TAREA ';
                    $subtitulo = 'TAREA: ' . $tarea->codigo_tarea . ' - ' . $tarea->nombre_tarea;
                    break;
                case '3':
                    $detalle = DetalleViatico::where('id', $request->detalle)->first();
                    $titulo .= 'DE GASTOS POR DETALLE ';
                    $subtitulo = 'DETALLE: ' . $detalle->descripcion;
                    break;
                case '4':
                    $sub_detalle = SubDetalleViatico::where('id', $request->subdetalle)->first();
                    $titulo .= 'DE GASTOS POR SUBDETALLE ';
                    $subtitulo = 'SUBDETALLE: ' . $sub_detalle->descripcion;
                    break;
                case '5':
                    $autorizador = Empleado::where('id', $request->autorizador)->first();
                    $titulo .= 'DE GASTOS POR AUTORIZADOR ';
                    $subtitulo = 'AUTORIZADOR: ' . $autorizador->nombres . ' ' . $autorizador->apellidos;
                    break;
                case '6':
                    $usuario = Empleado::where('id', $request->empleado)->first();
                    $titulo .= 'DE GASTOS POR EMPLEADO ';
                    $subtitulo = 'EMPLEADO: ' . $usuario->nombres . ' ' . $usuario->apellidos;
                    break;
                case '7':
                    $ruc = Gasto::where('ruc', $request->ruc)->first();
                    $titulo .= 'DE GASTOS POR RUC ';
                    $subtitulo = 'RUC: ' . $ruc->ruc;
                    break;
                case '9':
                    $ciudad = Canton::where('id', $request['ciudad'])->first();
                    $titulo .= 'DE GASTOS POR CIUDAD ';
                    $subtitulo = 'Ciudad: ' . $ciudad->canton;
                    break;
            }
            $titulo .= 'DEL ' . $fecha_inicio . ' AL ' . $fecha_fin . '';
            $tipo_filtro = $request->tipo_filtro;
            $reportes =  [
                'gastos' => $results,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'usuario' => $usuario,
                'titulo' => $titulo,
                'subtitulo' => $subtitulo,
                'tipo_filtro' => $tipo_filtro,
                'subdetalle' => $request->subdetalle,
            ];
            $vista = $imagen ? 'exports.reportes.reporte_consolidado.reporte_gastos_usuario_imagen_filtrado' : 'exports.reportes.reporte_consolidado.reporte_gastos_filtrado';
            $export_excel = new GastoFiltradoExport($reportes);
            $tamanio_papel = $imagen ? 'A2' : 'A4';
            return $this->reporteService->imprimir_reporte($tipo, $tamanio_papel, 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * It's a function that receives two parameters, one of them is a request object and the other is a
     * string
     *
     * @param request The request object.
     * @param tipo The type of report you want to generate.
     */
    private  function acreditacion($request, $tipo)
    {
        try {
            $date_inicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $acreditaciones = null;
            $usuario = null;
            if ($request->usuario == null) {
                $acreditaciones = Acreditaciones::with('usuario')
                    ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
                // Calcular la suma de los montos.
                $sumaMontos = $acreditaciones->sum('monto');
            } else {
                $acreditaciones = Acreditaciones::with('usuario')
                    ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                    ->where('id_usuario', $request->usuario)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
                $usuario = Empleado::where('id', $request->usuario)
                    ->first();
                $sumaMontos = $acreditaciones->sum('monto');
            }
            $nombre_reporte = 'reporte_saldoActual';
            $results = Acreditaciones::empaquetar($acreditaciones);
            $reportes =  ['acreditaciones' => $results, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'usuario' => $usuario, 'total' => $sumaMontos];
            $vista = 'exports.reportes.reporte_consolidado.reporte_acreditaciones_usuario';
            $export_excel = new AcreditacionesExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error acreditacion', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$e->getMessage()],
            ]);
        }
    }
    /**
     * A function that is used to generate a report of the expenses of a user.
     *
     * @param request The request object.
     * @param tipo The type of report you want to generate.
     */
    private  function gasto($request, $tipo, $imagen = false)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin =  $request->fecha_fin;
            $ultimo_saldo = 0;
            $acreditaciones = 0;
            $gastos_totales = 0;
            $transferencia = 0;
            $transferencia_recibida = 0;
            $total = 0;
            $usuario_canton = '';
            $fecha_anterior = '';
            $saldo_anterior = 0;
            if ($request->usuario == null) {
                $gastos = Gasto::with('empleado', 'detalleEstado', 'subDetalle', 'authEspecialUser', 'tarea')
                    ->where('estado', Gasto::APROBADO)
                    ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                    ->get();
                $usuario = '';
            } else {
                $fecha = Carbon::parse($fecha_inicio);
                $fecha_anterior =  $fecha->subDay()->format('Y-m-d');
                $saldo_anterior = SaldoGrupo::where('id_usuario', $request->usuario)
                    ->where('fecha', '<=', $fecha_anterior)
                    ->orderBy('created_at', 'desc')->limit(1)->first();
                if ($saldo_anterior != null) {
                    $fecha_anterior =  $saldo_anterior->fecha;
                }
                $ultimo_saldo = SaldoGrupo::where('id_usuario', $request->usuario)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->orderBy('id', 'desc')
                    ->first();
                $acreditaciones = Acreditaciones::with('usuario')
                    ->where('id_usuario', $request->usuario)
                    ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->sum('monto');

                $gastos = Gasto::with('empleado', 'detalleEstado', 'detalle_info', 'subDetalle', 'authEspecialUser', 'tarea')
                    ->where('estado', Gasto::APROBADO)
                    ->where('id_usuario', $request->usuario)
                    ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                    ->get();
                $gastos_totales =  $gastos->sum('total');
                $transferencia = Transferencias::where('usuario_envia_id', $request->usuario)
                    ->where('estado', 1)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->sum('monto');
                $transferencia_recibida = Transferencias::where('usuario_recibe_id', $request->usuario)
                    ->where('estado', 1)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->sum('monto');
                $saldo_old =  $saldo_anterior != null ? $saldo_anterior->saldo_actual : 0;
                $total = $saldo_old +  $acreditaciones - $transferencia + $transferencia_recibida - $gastos_totales;
                $empleado = Empleado::where('id', $request->usuario)->first();
                $usuario = $empleado->nombres . '' . ' ' . $empleado->apellidos;
                $usuario_canton =  $empleado->canton->canton;
            }
            $nombre_reporte = 'reporte_gastos';
            $results = Gasto::empaquetar($gastos);
            $reportes =  [
                'gastos' => $results,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'fecha_anterior' => $fecha_anterior,
                'usuario' => $usuario,
                'usuario_canton' => $usuario_canton,
                'saldo_anterior' =>  $saldo_anterior != null ? $saldo_anterior->saldo_actual : 0,
                'ultimo_saldo' => $ultimo_saldo,
                'total_suma' => $total,
                'acreditaciones' => $acreditaciones,
                'gastos_totales' => $gastos_totales,
                'transferencia' => $transferencia,
                'transferencia_recibida' => $transferencia_recibida
            ];
            $vista = $imagen ? 'exports.reportes.reporte_consolidado.reporte_gastos_usuario_imagen' : 'exports.reportes.reporte_consolidado.reporte_gastos_usuario';
            $export_excel = new GastoConsolidadoExport($reportes);
            $tamanio_papel = $imagen ? 'A2' : 'A4';
            return $this->reporteService->imprimir_reporte($tipo, $tamanio_papel, 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * La función `reporteEstadoCuenta` genera un informe consolidado de transacciones financieras para un
     * usuario específico dentro de un rango de fechas determinado.
     *
     * @param Request request La función `reporteEstadoCuenta` parece generar un informe basado en los
     * datos de la solicitud proporcionados y un tipo de informe. Calcula diversos datos financieros, como
     * saldos, transacciones y gastos de un usuario determinado dentro de un rango de fechas específico.
     * @param string tipo_reporte Tipo_reporte es un parámetro de cadena que especifica el tipo de informe
     * a generar. Podría ser algo como "PDF" o "Excel".
     *
     * @return La función `reporteEstadoCuenta` está devolviendo el resultado de llamar al método
     * `imprimir_reporte` del objeto ``. El método `imprimir_reporte` parece generar e
     * imprimir un informe basado en los datos y parámetros proporcionados. El valor de retorno del método
     * `imprimir_reporte` es lo que finalmente devuelve el método `reporteEstadoCuenta
     */
    private function reporteEstadoCuenta(Request $request, string $tipo_reporte)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $fecha = Carbon::parse($fecha_inicio);
            $fecha_anterior =  $fecha->subDay()->format('Y-m-d');

            $saldo_anterior = SaldoService::obtenerSaldoAnterior($request->usuario, $fecha_anterior);
            if ($saldo_anterior != null) {
                $fecha_anterior = $saldo_anterior->fecha;
            }
            $fecha_anterior =  $fecha->format('Y-m-d');

            $es_nuevo_saldo = SaldoService::existeSaldoNuevaTabla($fecha_inicio, $request->usuario);
            //Gastos
            $gastos = Gasto::with('empleado', 'detalle_info', 'subDetalle', 'authEspecialUser')
                ->selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('id_usuario', '=', $request->usuario)
                ->where(function ($query) {
                    $query->where('estado', '=', Gasto::APROBADO)
                        ->orWhere('estado', '=', Gasto::ANULADO);
                })
                ->get();
            $gastos = SaldoGrupo::verificarGastosRepetidosEnSaldoGrupo($gastos);
            //Transferencias
            $transferencias_enviadas = Transferencias::where('usuario_envia_id', $request->usuario)
                ->with('empleadoRecibe', 'empleadoEnvia')
                ->where(function ($query) {
                    $query->where('estado', '=', Transferencias::APROBADO)
                        ->orWhere('estado', '=', Transferencias::ANULADO);
                })
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencias_recibidas = Transferencias::where('usuario_recibe_id', $request->usuario)
                ->with('empleadoRecibe', 'empleadoEnvia')
                ->where(function ($query) {
                    $query->where('estado', '=', Transferencias::APROBADO)
                        ->orWhere('estado', '=', Transferencias::ANULADO);
                })
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            //Acreditaciones
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $request->usuario)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $ajuste_saldo = AjusteSaldoFondoRotativo::where('destinatario_id', $request->usuario)
                ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
                ->get();
            $ultimo_saldo = SaldoService::obtenerSaldoActualUltimaFecha($fecha_fin,  $request->usuario);
            $estado_cuenta_anterior = $request->fecha_inicio != '01-06-2023' ? $this->saldoService->EstadoCuentaAnterior($request->fecha_inicio, $request->usuario) : $saldo_anterior->saldo_actual;
            $saldo_anterior_db = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
            $salt_ant =  $estado_cuenta_anterior !== 0 ? $estado_cuenta_anterior : $saldo_anterior_db;
            $nuevo_elemento = [
                'item' => 1,
                'fecha' => $fecha_anterior,
                'fecha_creacion' =>  $saldo_anterior == null ? $fecha : $saldo_anterior->created_at,
                'num_comprobante' => '',
                'descripcion' => 'SALDO ANTERIOR',
                'observacion' => '',
                'ingreso' => 0,
                'gasto' => 0,
                'saldo_anterior' => $salt_ant,
                'saldo' => $saldo_anterior_db,
            ];
            //Unir todos los reportes
            $saldos_fondos = Saldo::with('saldoable')->where('empleado_id', $request->usuario)->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();
            $reportes_unidos_historico = $gastos->merge($transferencias_enviadas)->merge($transferencias_recibidas)->merge($acreditaciones)->merge($ajuste_saldo);
            $reportes_unidos = $es_nuevo_saldo ? $reportes_unidos = Saldo::empaquetarCombinado( $nuevo_elemento,$saldos_fondos, $request->usuario) : SaldoGrupo::empaquetarCombinado($nuevo_elemento,$reportes_unidos_historico, $request->usuario);
            $sub_total = 0;
            $nuevo_saldo = $ultimo_saldo != null ?  $ultimo_saldo->saldo_actual : 0;
            $empleado = Empleado::where('id', $request->usuario)->first();
            $usuario = User::where('id', $empleado->usuario_id)->first();
            $nombre_reporte = 'reporte_estado_cuenta';

            $reportes =  [
                'fecha_anterior' => $fecha_anterior,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'empleado' => $empleado,
                'usuario' => $usuario,
                'saldo_anterior' =>  $saldo_anterior_db,
                'reportes_unidos' => $reportes_unidos,
                'nuevo_saldo' => $nuevo_saldo,
                'sub_total' => $sub_total,
            ];

            $vista = 'exports.reportes.reporte_consolidado.reporte_movimiento_saldo';
            $export_excel = new EstadoCuentaExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo_reporte, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * La función "reporte_consolidado" genera un informe consolidado basado en la solicitud y el tipo
     * dado.
     *
     * @param request El parámetro `` es un objeto que contiene los datos enviados en la
     * solicitud HTTP. Se utiliza para recuperar los valores de las propiedades `fecha_inicio` y
     * `fecha_fin`.
     * @param tipo El parámetro "tipo" se utiliza para determinar el tipo de informe a generar.
     * Probablemente sea un valor de cadena que especifica el formato del informe, como "pdf" o "excel".
     *
     * @return the result of the method call `->reporteService->imprimir_reporte(, 'A4',
     * 'portail', , , , )`.
     */
    private  function reporteConsolidado($request, $tipo)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $fecha = Carbon::parse($fecha_inicio);
            $fecha_anterior =  $fecha->subDay()->format('Y-m-d');
            $saldo_anterior = SaldoService::obtenerSaldoAnterior($request->usuario, $fecha_anterior);
            if ($saldo_anterior != null) {
                $fecha =  Carbon::parse($saldo_anterior->fecha);
                $fecha_anterior =  $fecha->format('Y-m-d');
            }
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $request->usuario)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $gastos_reporte = Gasto::with('empleado', 'detalle_info', 'subDetalle', 'authEspecialUser', 'tarea')->selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', '=', 1)
                ->where('id_usuario', '=',  $request->usuario)
                ->get();
            $gastos = $gastos_reporte->sum('total');
            $gastos_reporte = Gasto::empaquetar($gastos_reporte);
            $transferencias_enviadas = Transferencias::where('usuario_envia_id', $request->usuario)
                ->with('empleadoRecibe', 'empleadoEnvia')
                ->where('estado', Transferencias::APROBADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencia =  $transferencias_enviadas->sum('monto');
            $transferencias_recibidas = Transferencias::where('usuario_recibe_id', $request->usuario)
                ->with('empleadoRecibe', 'empleadoEnvia')
                ->where('estado', Transferencias::APROBADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencia_recibida = $transferencias_recibidas->sum('monto');
            $ajuste_saldo_ingreso_reporte = AjusteSaldoFondoRotativo::whereBetween(DB::raw('DATE(created_at)'), [$fecha_inicio, $fecha_fin])
                ->where('destinatario_id', $request->usuario)
                ->where('tipo', AjusteSaldoFondoRotativo::INGRESO)
                ->get();
            $ajuste_saldo_ingreso = $ajuste_saldo_ingreso_reporte->sum('monto');
            $ajuste_saldo_ingreso_reporte = AjusteSaldoFondoRotativo::empaquetar($ajuste_saldo_ingreso_reporte);
            $ajuste_saldo_egreso_reporte = AjusteSaldoFondoRotativo::whereBetween(DB::raw('DATE(created_at)'), [$fecha_inicio, $fecha_fin])
                ->where('destinatario_id', $request->usuario)
                ->where('tipo', AjusteSaldoFondoRotativo::EGRESO)
                ->get();
            $ajuste_saldo_egreso = $ajuste_saldo_egreso_reporte->sum('monto');
            $ajuste_saldo_egreso_reporte = AjusteSaldoFondoRotativo::empaquetar($ajuste_saldo_egreso_reporte);
            $ultimo_saldo =  SaldoService::obtenerSaldoEmpleadoEntreFechas($fecha_inicio, $fecha_fin, $request->usuario);
            $sub_total = 0;
            $nuevo_saldo =   $ultimo_saldo != null ? $ultimo_saldo->saldo_actual : 0;
            $saldo_old =  $saldo_anterior != null ? $saldo_anterior->saldo_actual : 0;
            $total = ($saldo_old +  $acreditaciones - $transferencia + $transferencia_recibida - $gastos) + $ajuste_saldo_ingreso - $ajuste_saldo_egreso;
            $empleado = Empleado::where('id', $request->usuario)->first();

            $usuario = User::where('id', $empleado->usuario_id)->first();
            $nombre_reporte = 'reporte_consolidado';
            $reportes =  [
                'fecha_anterior' => $fecha_anterior,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'empleado' => $empleado,
                'usuario' => $usuario,
                'saldo_anterior' => $saldo_anterior != null ? $saldo_anterior->saldo_actual : 0,
                'acreditaciones' => $acreditaciones,
                'gastos' => $gastos,
                'gastos_reporte' => $gastos_reporte,
                'transferencia' => $transferencia,
                'transferencia_recibida' => $transferencia_recibida,
                'transferencias_enviadas' => $transferencias_enviadas,
                'transferencias_recibidas' => $transferencias_recibidas,
                'ajuste_saldo_ingreso' => $ajuste_saldo_ingreso,
                'ajuste_saldo_ingreso_reporte' => $ajuste_saldo_ingreso_reporte,
                'ajuste_saldo_egreso' =>  $ajuste_saldo_egreso,
                'ajuste_saldo_egreso_reporte' => $ajuste_saldo_egreso_reporte,
                'nuevo_saldo' => $nuevo_saldo,
                'sub_total' => $sub_total,
                'total_suma' => $total
            ];
            $vista = 'exports.reportes.reporte_consolidado.reporte_consolidado_usuario';
            $export_excel = new ConsolidadoExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * La función `reporte_transferencia` genera un informe sobre transacciones de transferencia en función
     * de los parámetros de solicitud proporcionados.
     *
     * @param request Según el fragmento de código proporcionado, la función `reporte_transferencia` parece
     * generar un informe relacionado con las transferencias según los parámetros de solicitud dados. Sin
     * embargo, faltan los detalles de los parámetros de la solicitud en el contexto proporcionado.
     * @param tipo El parámetro `tipo` en la función `reporte_transferencia` se utiliza para determinar el
     * tipo de informe a generar. Se pasa como argumento al método `imprimir_reporte` del `reporteService`.
     * El parámetro `tipo` probablemente especifica si el informe debe generarse
     *
     * @return La función `reporte_transferencia` está devolviendo el resultado de llamar al método
     * `imprimir_reporte` del objeto `reporteService`. Los parámetros pasados a `imprimir_reporte` son
     * ``, `'A4'`, `'portail'`, ``, ``, `` y ``.
     */
    private  function reporteTransferencia($request, $tipo)
    {
        try {
            $fecha_inicio =  $request->fecha_inicio;
            $fecha_fin =  $request->fecha_fin;
            $fecha = Carbon::parse($fecha_inicio);
            $fecha_anterior =  $fecha->subDay()->format('Y-m-d');
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
            if ($request->usuario != null) {
                $empleado = Empleado::where('id', $request->usuario)->first();
                $usuario = User::where('id', $empleado->usuario_id)->first();
                $transferencias_enviadas = Transferencias::where('usuario_envia_id', $request->usuario)
                    ->with('empleadoRecibe', 'empleadoEnvia')
                    ->where('estado', Transferencias::APROBADO)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
                $transferencia_enviada =  $transferencias_enviadas->sum('monto');
                $transferencias_recibidas = Transferencias::where('usuario_recibe_id', $request->usuario)
                    ->with('empleadoRecibe', 'empleadoEnvia')
                    ->where('estado', Transferencias::APROBADO)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
                $transferencia_recibida = $transferencias_recibidas->sum('monto');
            }

            $reportes =  [
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
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * La función `gastocontabilidad` recupera gastos dentro de un rango de fechas específico para un
     * usuario determinado y devuelve los resultados en formato JSON.
     *
     * @param Request request La función `gastocontabilidad` toma como parámetro un objeto `Request`. Este
     * objeto `Request` probablemente contenga datos enviados por el cliente, como `fecha_inicio` (fecha de
     * inicio) y `fecha_fin` (fecha de finalización) para filtrar gastos.
     *
     * @return La función `gastocontabilidad` está devolviendo una respuesta JSON que contiene los
     * resultados de la consulta de gastos (`) dentro de un rango de fechas específico. Los gastos
     * se recuperan de la base de datos con base en el ID de usuario (->usuario`) y el rango de
     * fechas especificado por los parámetros `fecha_inicio` y `fecha_fin`. Luego, los gastos se formatean
     * utilizando `GastoResource`
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
                ->orderBy('fecha_viat', 'asc')
                ->get();
            $results = GastoResource::collection($gastos);
            return response()->json(compact('results'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
}
