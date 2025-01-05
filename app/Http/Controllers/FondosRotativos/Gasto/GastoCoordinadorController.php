<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Events\SolicitudFondosEvent;
use App\Exports\SolicitudFondosExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\GastoCoordinadorRequest;
use App\Http\Resources\FondosRotativos\Gastos\GastoCoordinadorResource;
use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\GastoCoordinador;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
            $results = GastoCoordinador::with('empleado', 'motivoGasto', 'canton')->orderBy('fecha_gasto', 'desc')->get();
        } else {
            $results = GastoCoordinador::with('empleado', 'motivoGasto', 'canton')->where('id_usuario', $usuario->empleado->id)->orderBy('fecha_gasto', 'desc')->get();
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
    public function store(GastoCoordinadorRequest $request)
    {
        DB::beginTransaction();
        try {
            $datos = $request->validated();
            $gasto_cordinador = GastoCoordinador::create($datos);
            $modelo = new GastoCoordinadorResource($gasto_cordinador);
            $gasto_cordinador->detalleMotivoGasto()->sync($request->motivo);
            event(new SolicitudFondosEvent($gasto_cordinador));
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al actualizar gasto' => [$e->getMessage()],
            ]);
        }
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
    public function update(GastoCoordinadorRequest $request, GastoCoordinador $gasto_coordinador)
    {
        DB::beginTransaction();
        try {
            $datos = $request->validated();
            $gasto_coordinador->update($datos);
            $modelo = new GastoCoordinadorResource($gasto_coordinador->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al actualizar gasto' => [$e->getMessage()],
            ]);
        }
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
    /**
     * La función `reporte` genera un informe basado en la entrada del usuario y maneja excepciones.
     *
     * @param Request request La función "reporte" que proporcionó parece estar manejando la generación de
     * un informe basado en los datos de la solicitud. Aquí hay un desglose de la función:
     * @param string tipo El parámetro `tipo` en la función `reporte` parece indicar el tipo de informe que
     * se generará. Es un parámetro de cadena que probablemente especifica el formato o tipo de informe,
     * como PDF, Excel.
     *
     * @return La función `reporte` devuelve el resultado de llamar al método `imprimir_reporte` en el
     * objeto `reporteService`. El método está siendo llamado con los parámetros ``, `'A4'`,
     * `'landscape'`, ``, ``, `` y ``.
     */
    public function reporte(Request $request, string $tipo)
    {
        try {
            $datos = $request->all();
            $date_inicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $usuario = null;
            if ($request->usuario !== null) {
                $results = GastoCoordinador::where('id_usuario', $request->usuario)->whereBetween('fecha_gasto', [$fecha_inicio, $fecha_fin])->get();
                $usuario = Empleado::where('id', $request->usuario)->first();
            } else {
                $results = GastoCoordinador::whereBetween('fecha_gasto', [$fecha_inicio, $fecha_fin])->get();
            }
            $solicitudes = GastoCoordinador::empaquetar($results);
            $nombre_reporte = 'reporte_solicitud_fondos_del' . $fecha_inicio . '-' . $fecha_fin . 'de' .  Auth::user()->empleado->nombres . ' ' . Auth::user()->apellidos;
            $vista = 'exports.reportes.solicitud_fondos';
            $reportes = compact('solicitudes', 'usuario', 'fecha_inicio', 'fecha_fin');
            $export_excel = new SolicitudFondosExport($reportes);
            return $this->reporteService->imprimirReporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
}
