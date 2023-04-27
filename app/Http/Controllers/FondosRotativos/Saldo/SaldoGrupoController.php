<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Exports\ConsolidadoExport;
use App\Exports\GastoFiltradoExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Saldo\SaldoGrupoResource;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Gasto\EstadoGasto;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Src\Shared\Utils;
use App\Exports\SaldoActualExport;
use App\Http\Resources\FondosRotativos\Gastos\GastoResource;
use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Gasto\SubDetalleViatico;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Proyecto;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Src\App\FondosRotativos\ReportePdfExcelService;

class SaldoGrupoController extends Controller
{
    private $entidad = 'saldo_grupo';
    private $reporteService;
    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
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
        $results = SaldoGrupo::with('usuario')->ignoreRequest(['campos'])->filter()->get();
        $results = SaldoGrupoResource::collection($results);
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
        $SaldoGrupo = SaldoGrupo::where('id', $id)->first();
        $modelo = new SaldoGrupoResource($SaldoGrupo);
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
        $modelo = new SaldoGrupoResource($modelo);
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
    public function saldo_actual_usuario($id)
    {
        $saldo_actual = SaldoGrupo::where('id_usuario', $id)->orderBy('id', 'desc')->first();
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
    public function saldo_actual(Request $request, $tipo)
    {
        try {
            $id = $request->usuario != null ?  $request->usuario : 0;
            $saldos_actual_user = $request->usuario == null ?
                SaldoGrupo::with('usuario')->whereIn('id', function ($sub) {
                    $sub->selectRaw('max(id)')->from('saldo_grupo')->groupBy('id_usuario');
                })->get()
                : SaldoGrupo::with('usuario')->where('id_usuario', $id)->orderBy('id', 'desc')->first();
            $tipo_reporte = $request->usuario != null ? 'usuario' : 'todos';
            $results = SaldoGrupo::empaquetarListado($saldos_actual_user, $tipo_reporte);
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
     * It's a function that returns a function that returns a function
     *
     * @param Request request The request object.
     * @param tipo 1 = Acreditaciones, 2 = Gasos, 3 = Consolidado
     */
    public function consolidado(Request $request, $tipo)
    {
        try {
            switch ($request->tipo_saldo) {
                case '1':
                    return $this->acreditacion($request, $tipo);
                    break;
                case '2':
                    return $this->gasto($request, $tipo);
                    break;
                case '3':
                    return $this->reporte_consolidado($request, $tipo);
                    break;
                case '4':
                    return $this->reporte_movimiento($request, $tipo);
                    break;
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * It returns a function based on the value of the variable ->tipo_saldo
     *
     * @param Request request The request object.
     * @param tipo 1 = Acreditaciones, 2 = Gasos Filtrado, 3 = Consolidado
     */
    public function consolidado_filtrado(Request $request, $tipo)
    {
        try {
            switch ($request->tipo_saldo) {
                case '1':
                    return $this->acreditacion($request, $tipo);
                    break;
                case '2':
                    return $this->gasto_filtrado($request, $tipo);
                    break;
                case '3':
                    return $this->reporte_consolidado($request, $tipo);
                    break;
                case '4':
                    return $this->reporte_movimiento($request, $tipo);
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
    private function gasto_filtrado(Request $request, $tipo)
    {
        try {
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $request['id_proyecto'] =  $request['proyecto'];
            $request['sub_detalle'] = $request['id_sub_detalle'];
            $request['id_usuario'] = $request['usuario'];
            $request['id_estado'] = $request['estado'];
            $request['id_tarea'] = $request['tarea'];
            $request['aut_especial'] = $request['autorizador'];
            $gastos = Gasto::ignoreRequest([
                'tipo_saldo',
                'tipo_filtro',
                'sub_detalle',
                'usuario', 'tarea',
                'autorizador',
                'fecha_inicio',
                'fecha_fin',
                'estado',
                'detalle',
                'proyecto',
            ])
                ->filter($request->all())
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', Gasto::APROBADO)
                ->with(
                    'empleado_info',
                    'detalle_estado',
                    'sub_detalle_info',
                    'proyecto_info'
                )->get();
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
                    $sub_detalle = SubDetalleViatico::where('id', $request->sub_detalle)->first();
                    $titulo .= 'DE GASTOS POR SUBDETALLE ';
                    $subtitulo = 'SUBDETALLE: ' . $sub_detalle->descripcion;
                    break;
                case '5':
                    $autorizador = Empleado::where('id', $request->autorizador)->first();
                    $titulo .= 'DE GASTOS POR AUTORIZADOR ';
                    $subtitulo = 'AUTORIZADOR: ' . $autorizador->nombres . ' ' . $autorizador->apellidos;
                    break;
                case '6':
                    $usuario = Empleado::where('id', $request->usuario)->first();
                    $titulo .= 'DE GASTOS POR EMPLEADO ';
                    $subtitulo = 'EMPLEADO: ' . $usuario->nombres . ' ' . $usuario->apellidos;
                    break;
            }
            $titulo .= 'DEL ' . $fecha_inicio . ' AL ' . $fecha_fin . '';
            $reportes =  [
                'gastos' => $results,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'usuario' => $usuario,
                'titulo' => $titulo,
                'subtitulo' => $subtitulo,
            ];
            $vista = 'exports.reportes.reporte_consolidado.reporte_gastos_filtrado';
            $export_excel = new GastoFiltradoExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
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
            $date_inicio = Carbon::createFromFormat('d-m-Y', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('d-m-Y', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->where('id_usuario', $request->usuario)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $usuario = Empleado::where('id', $request->usuario)
                ->first();
            $nombre_reporte = 'reporte_saldoActual';
            $results = Acreditaciones::empaquetar($acreditaciones);
            $reportes =  ['acreditaciones' => $results, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'usuario' => $usuario];
            $vista = 'exports.reportes.reporte_consolidado.reporte_acreditaciones_usuario';
            $export_excel = new SaldoActualExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * A function that is used to generate a report of the expenses of a user.
     *
     * @param request The request object.
     * @param tipo The type of report you want to generate.
     */
    private  function gasto($request, $tipo)
    {
        try {
            $date_inicio = Carbon::createFromFormat('d-m-Y', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('d-m-Y', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $gastos = Gasto::with('empleado_info', 'detalle_estado', 'sub_detalle_info')
                ->where('estado', Gasto::APROBADO)
                ->where('id_usuario', $request->usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->get();
            $usuario = Empleado::where('id', $request->usuario)->first();
            $nombre_reporte = 'reporte_gastos';
            $results = Gasto::empaquetar($gastos);
            $reportes =  ['gastos' => $results, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'usuario' => $usuario];
            $vista = 'exports.reportes.reporte_consolidado.reporte_gastos_usuario';
            $export_excel = new SaldoActualExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    private function reporte_movimiento(Request $request, $tipo)
    {

        try {
            $date_inicio = Carbon::createFromFormat('d-m-Y', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('d-m-Y', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $fecha = Carbon::parse($fecha_inicio);
            $fecha_anterior =  $fecha->subDay()->format('Y-m-d');
            $saldo_anterior = SaldoGrupo::whereDate('fecha', $fecha_anterior)
                ->where('id_usuario', $request->usuario)
                ->first();
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $request->usuario)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $gastos = Gasto::with('empleado_info', 'detalle_estado', 'sub_detalle_info')
                ->where('estado', 1)
                ->where('id_usuario', $request->usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->sum('total');

            $transferencia = Transferencias::where('usuario_envia_id', $request->usuario)
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');

            $transferencia_recibida = Transferencias::where('usuario_recibe_id', $request->usuario)
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');

            //Gastos
            $gastos_reporte = Gasto::with('empleado_info', 'detalle_info', 'sub_detalle_info', 'aut_especial_user')->selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', '=', 1)
                ->where('id_usuario', '=',  $request->usuario)
                ->get();
            //Transferencias
            $transferencias_enviadas = Transferencias::where('usuario_envia_id', $request->usuario)
                ->with('usuario_recibe', 'usuario_envia')
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencias_recibidas = Transferencias::where('usuario_recibe_id', $request->usuario)
                ->with('usuario_recibe', 'usuario_envia')
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            //Acreditaciones
            $acreditaciones_reportes = Acreditaciones::with('usuario')
                ->where('id_usuario', $request->usuario)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            //Unir todos los reportes
            $reportes = array_merge($gastos_reporte->toArray(), $transferencias_enviadas->toArray(), $transferencias_recibidas->toArray(), $acreditaciones_reportes->toArray());
            Log::channel('testing')->info('Log', ['reportes', $reportes]);
            $ultimo_saldo = SaldoGrupo::where('id_usuario', $request->usuario)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->orderBy('id', 'desc')
                ->first();
            $sub_total = 0;
            $nuevo_saldo =   $ultimo_saldo != null ? $ultimo_saldo->saldo_actual : 0;
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
                'nuevo_saldo' => $nuevo_saldo,
                'sub_total' => $sub_total,
            ];
            $vista = 'exports.reportes.reporte_consolidado.reporte_movimiento_saldo';
            $export_excel = new ConsolidadoExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
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
    private  function reporte_consolidado($request, $tipo)
    {
        try {
            $date_inicio = Carbon::createFromFormat('d-m-Y', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('d-m-Y', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $fecha = Carbon::parse($fecha_inicio);
            $fecha_anterior =  $fecha->subDay()->format('Y-m-d');
            $saldo_anterior = SaldoGrupo::whereDate('fecha', $fecha_anterior)
                ->where('id_usuario', $request->usuario)
                ->first();

            Log::channel('testing')->info('Log', ['saldo_anterior', $saldo_anterior]);
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $request->usuario)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $gastos = Gasto::with('empleado_info', 'detalle_estado', 'sub_detalle_info')
                ->where('estado', 1)
                ->where('id_usuario', $request->usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->sum('total');
            $gastos_reporte = Gasto::with('empleado_info', 'detalle_info', 'sub_detalle_info', 'aut_especial_user')->selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', '=', 1)
                ->where('id_usuario', '=',  $request->usuario)
                ->get();
            $transferencia = Transferencias::where('usuario_envia_id', $request->usuario)
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $transferencias_enviadas = Transferencias::where('usuario_envia_id', $request->usuario)
                ->with('usuario_recibe', 'usuario_envia')
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencia_recibida = Transferencias::where('usuario_recibe_id', $request->usuario)
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $transferencias_recibidas = Transferencias::where('usuario_recibe_id', $request->usuario)
                ->with('usuario_recibe', 'usuario_envia')
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $ultimo_saldo = SaldoGrupo::where('id_usuario', $request->usuario)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->orderBy('id', 'desc')
                ->first();
            $sub_total = 0;
            $nuevo_saldo =   $ultimo_saldo != null ? $ultimo_saldo->saldo_actual : 0;
            $total = $saldo_anterior != null ? $saldo_anterior->saldo_actual : 0 + (-$transferencia + $transferencia_recibida) + $acreditaciones - $gastos;
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
                'nuevo_saldo' => $nuevo_saldo,
                'sub_total' => $sub_total,
                'total_suma' => $total
            ];
            $vista = 'exports.reportes.reporte_consolidado.reporte_consolidado_usuario';
            $export_excel = new ConsolidadoExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'portail', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    public function gastocontabilidad(Request $request)
    {
        try {
            $date_inicio = Carbon::createFromFormat('d-m-Y', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('d-m-Y', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $gastos = Gasto::with('empleado_info', 'detalle_estado', 'sub_detalle_info')
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
