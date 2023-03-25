<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Events\SolicitudFondosEvent;
use App\Exports\GastoExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Gastos\GastoCoordinadorResource;
use App\Models\FondosRotativos\Gasto\GastoCoordinador;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\Shared\Utils;

class GastoCoordinadorController extends Controller
{
    private $entidad = 'gasto_coordinador';
    private $reporteService;
    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->middleware('can:puede.ver.reporte_solicitud_fondo')->only(['reporte']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        if ($usuario_ac->hasRole('CONTABILIDAD')) {
            $results = GastoCoordinador::with('usuario_info', 'motivo_info', 'lugar_info')->get();
        } else {
            $results = GastoCoordinador::with('usuario_info', 'motivo_info', 'lugar_info')->where('id_usuario', $usuario->id)->get();
        }
        $results = GastoCoordinadorResource::collection($results);
        return compact('results');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['fecha_gasto'] = date('Y-m-d');
        $datos['id_motivo'] = $request->motivo;
        $datos['id_lugar'] = $request->lugar;
        //usuario autenticado
        $user = Auth::user();
        $datos['id_usuario'] = $user->id;
        $modelo = GastoCoordinador::create($datos);
        $contabilidad = User::with('empleado')->where('name', 'IVALAREZO')->first();
        event(new SolicitudFondosEvent($modelo, $contabilidad));
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GastoCoordinador $gasto_coordinador)
    {
        $modelo = new GastoCoordinadorResource($gasto_coordinador);
        return response()->json(compact('modelo'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $datos = $request->all();
        $modelo = GastoCoordinador::find($id);
        $modelo->update($datos);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = GastoCoordinador::find($id);
        $modelo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function reporte(Request $request,$tipo)
    {
        try{
            $datos = $request->all();
            $date_inicio = Carbon::createFromFormat('d-m-Y', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('d-m-Y', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $results = GastoCoordinador::where('id_usuario',$request->usuario)->whereBetween('fecha_gasto', [$fecha_inicio, $fecha_fin])->get();
            $solicitudes = GastoCoordinadorResource::collection($results);
            Log::channel('testing')->info('Log', ['solicitudes', $solicitudes]);
            $nombre_reporte = 'reporte_solicitud_fondos_del' . $fecha_inicio . '-' . $fecha_fin . 'de' .  Auth::user()->empleado->nombres . ' ' . Auth::user()->apellidos;
            $vista = 'exports.reportes.solicitud_fondos';
            $export_excel = new GastoExport(null);
            $reportes = compact('solicitudes', 'fecha_inicio', 'fecha_fin');
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }

    }
}
