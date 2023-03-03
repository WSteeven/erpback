<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

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
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Gasto\Gasto;
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
            $export_excel= new SaldoActualExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo,'A4','portail', $reportes, $nombre_reporte,$vista,$export_excel);
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
            }
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
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $request->usuario)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $usuario = User::with('empleado')
                ->where('id', $request->usuario)
                ->first();
            $nombre_reporte = 'reporte_saldoActual';
            $results = Acreditaciones::empaquetar($acreditaciones);
            $reportes =  ['acreditaciones' => $results, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'usuario' => $usuario];
            $vista = 'exports.reportes.reporte_consolidado.reporte_acreditaciones_usuario';
            $export_excel= new SaldoActualExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo,'A4','portail', $reportes, $nombre_reporte,$vista,$export_excel);
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
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $gastos = Gasto::with('usuario_info', 'detalle_estado', 'sub_detalle_info')
                ->where('id_usuario', $request->usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->get();
            $usuario = User::with('empleado')->where('id', $request->usuario)->first();
            $nombre_reporte = 'reporte_gastos';
            $results = Gasto::empaquetar($gastos);
            $reportes =  ['gastos' => $results, 'fecha_inicio' => $request->fecha_inicio, 'fecha_fin' => $request->fecha_fin, 'usuario' => $usuario];
            $vista = 'exports.reportes.reporte_consolidado.reporte_gastos_usuario';
            $export_excel= new SaldoActualExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo,'A4','portail', $reportes, $nombre_reporte,$vista,$export_excel);
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
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $fecha_anterior = date('Y-m-d', strtotime($request->fecha_inicio . '- 1 day'));
            $saldo_anterior = SaldoGrupo::where('id_usuario', $request->usuario)
                ->where('fecha', $fecha_anterior)
                ->first();
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $request->usuario)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $gastos = Gasto::with('usuario_info', 'detalle_estado', 'sub_detalle_info')
                ->where('id_usuario', $request->usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->sum('total');
            $total = $saldo_anterior != null? $saldo_anterior->saldo_actual:0 + $acreditaciones - $gastos;
            $usuario = User::with('empleado')->where('id', $request->usuario)->first();
            $nombre_reporte = 'reporte_consolidado';
            $reportes =  [
                'fecha_anterior'=> $fecha_anterior,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'usuario' => $usuario,
                'saldo_anterior' => $saldo_anterior != null? $saldo_anterior->saldo_actual:0,
                'acreditaciones' => $acreditaciones,
                'gastos' => $gastos,
                'total_suma' => $total
            ];
            $vista = 'exports.reportes.reporte_consolidado.reporte_consolidado_usuario';
            $export_excel= new SaldoActualExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo,'A4','portail', $reportes, $nombre_reporte,$vista,$export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
}
