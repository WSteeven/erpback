<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Events\FondoRotativoEvent;
use App\Exports\AutorizacionesExport;
use App\Exports\GastoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\GastoRequest;
use App\Http\Resources\FondosRotativos\Gastos\GastoResource;
use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\EmpleadoService;
use Src\App\FondosRotativos\GastoService;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\FondosRotativos\SaldoService;
use Src\Shared\Utils;


class GastoController extends Controller
{
    private $entidad = 'gasto';
    private $reporteService;
    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->middleware('can:puede.ver.gasto')->only('index', 'show');
        $this->middleware('can:puede.crear.gasto')->only('store');
        $this->middleware('can:puede.editar.gasto')->only('update');
        $this->middleware('can:puede.eliminar.gasto')->only('destroy');
        $this->middleware('can:puede.ver.reporte_autorizaciones')->only('reporte_autorizaciones');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fechaActual = Carbon::now();
        $fechaViatico = $fechaActual->subMonths(6)->format('Y-m-d'); //Se consultara los gastos cuya fecha sea posterior a los ultimos 6 meses
        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        $results = [];
        if ($usuario_ac->hasRole([User::ROL_CONTABILIDAD, User::ROL_ADMINISTRADOR])) {
            $results = Gasto::ignoreRequest(['campos'])->with('detalle_info', 'subDetalle', 'authEspecialUser', 'EstadoViatico', 'tarea', 'proyecto')->where('fecha_viat', '>=', $fechaViatico)->filter()->orderBy('id', 'desc')->get();
            $results = GastoResource::collection($results);
            return response()->json(compact('results'));
        } else {
            $usuario = Auth::user()->empleado;
            $results = Gasto::where('id_usuario', $usuario->id)->orwhere('aut_especial', $usuario->id)->ignoreRequest(['campos'])->with('detalle_info', 'subDetalle', 'authEspecialUser', 'EstadoViatico', 'tarea', 'proyecto')->filter()->orderBy('id', 'desc')->get();
            $results = GastoResource::collection($results);
            return response()->json(compact('results'));
        }
    }
    public function autorizacionesGastos(Request $request)
    {
        try {
            $usuario_autenticado =  Auth::user();
            $results = [];
            if (!$usuario_autenticado->hasRole('ADMINISTRADOR')) {
                $results = Gasto::where('aut_especial', $usuario_autenticado->empleado->id)->ignoreRequest(['campos'])->with('detalle_info', 'authEspecialUser', 'EstadoViatico', 'tarea', 'proyecto')->filter()->get();
                $results = GastoResource::collection($results);
                return response()->json(compact('results'));
            } else {
                $fechaActual = Carbon::now();
                $fechaViatico = $fechaActual->subMonths(6)->format('Y-m-d');
                $results = Gasto::ignoreRequest(['campos'])->with('detalle_info', 'subDetalle', 'authEspecialUser', 'EstadoViatico', 'tarea', 'proyecto')->where('fecha_viat', '>=', $fechaViatico)->filter()->get();
                $results = GastoResource::collection($results);
                return response()->json(compact('results'));
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al consultar' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al aprobar el gasto' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function store(GastoRequest $request)
    {
        DB::beginTransaction();
        try {
            $datos = $request->validated();
            $datos = GastoService::convertirComprobantesBase64Url($datos);
            //Guardar Registro
            $gasto = Gasto::create($datos);
            $modelo = new GastoResource($gasto);
            $gasto_service = new GastoService($gasto);
            //Guardar en tabla de destalle gasto
            $gasto->subDetalle()->sync($request->sub_detalle);
            $gasto_service->crearBeneficiarios($request->beneficiarios);
            $gasto_service->validarGastoVehiculo($request);
            event(new FondoRotativoEvent($gasto));
            $modelo = new GastoResource($modelo);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function update(GastoRequest $request, Gasto $gasto)
    {
        $datos = $request->validated();
        $gasto->update($datos);
        $modelo = new GastoResource($gasto->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * It shows the gasto
     *
     * @param Gasto gasto The model that will be used to retrieve the data.
     *
     * @return A JSON object with the data of the gasto.
     */
    public function show(Gasto $gasto)
    {
        $modelo = new GastoResource($gasto);
        return response()->json(compact('modelo'), 200);
    }

    /**
     * La función `destroy` elimina una instancia de Gasto y devuelve una respuesta JSON con un mensaje.
     *
     * @param Gasto gasto La función `destruir` que proporcionó se utiliza para eliminar un objeto `Gasto`
     * de la base de datos. Luego de eliminar el objeto `Gasto`, recupera un mensaje usando el método
     * `Utils::obtenerMensaje` para la acción 'destruir' sobre la entidad. Finalmente, devuelve un JSON.
     *
     * @return Una respuesta JSON que contiene el mensaje obtenido del método `Utils::obtenerMensaje` luego
     * de eliminar la entidad `Gasto`.
     */
    public function destroy(Gasto $gasto)
    {
        $gasto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
    /**
     * It generates a report of the expenses of a user in a given period of time.
     * </code>
     *
     * @param Request request The request object.
     * @param tipo The type of file you want to generate.
     *
     * @return The data is being returned in the form of a collection.
     */
    public function generarReporte(Request $request, $tipo)
    {
        try {
            $datos_usuario_logueado = $request->usuario == null ? Empleado::where('id', Auth::user()->empleado->id)->first() : Empleado::where('id', $request->usuario)->first();
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $fecha_anterior = date('Y-m-d', strtotime($fecha_inicio . '- 1 day'));
            $saldo_anterior_data = SaldoService::obtenerSaldoEmpleadoFecha($fecha_anterior, $request->usuario);
            $saldo_anterior = $saldo_anterior_data != null ? $saldo_anterior_data->saldo_actual : 0.0;
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $datos_usuario_logueado->id)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $gastos_reporte = Gasto::with('empleado', 'detalle_info', 'subDetalle', 'authEspecialUser')->selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', '=', Gasto::APROBADO)
                ->where('id_usuario', '=',  $datos_usuario_logueado->id)
                ->get();
            $gastos_realizados = $gastos_reporte->sum('total');
            $transferencias_enviadas = Transferencias::where('usuario_envia_id',  $datos_usuario_logueado->id)
                ->with('empleadoRecibe', 'empleadoEnvia')
                ->where('estado', Transferencias::APROBADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencia_enviada = $transferencias_enviadas->sum('monto');
            $transferencias_recibidas = Transferencias::where('usuario_recibe_id', $datos_usuario_logueado->id)
                ->with('empleadoRecibe', 'empleadoEnvia')
                ->where('estado',  Transferencias::APROBADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencia_recibida = $transferencias_recibidas->sum('monto');
            $ultimo_saldo = SaldoService::obtenerSaldoEmpleadoEntreFechas($fecha_inicio, $fecha_fin, $datos_usuario_logueado->id);
            $ultimo_saldo = $ultimo_saldo == null ? 0.0 : $ultimo_saldo->saldo_actual;
            $datos_saldo_depositados_semana = Acreditaciones::with('usuario')
                ->where('id_usuario', $datos_usuario_logueado->id)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $sub_total = 0;
            $reportes = compact(
                'fecha_inicio',
                'fecha_fin',
                'datos_usuario_logueado',
                'acreditaciones',
                'gastos_realizados',
                'transferencia_enviada',
                'transferencias_enviadas',
                'transferencia_recibida',
                'transferencias_recibidas',
                'saldo_anterior',
                'ultimo_saldo',
                'datos_saldo_depositados_semana',
                'gastos_reporte',
                'sub_total'

            );
            $nombre_reporte = 'reporte_' . $fecha_inicio . '-' . $fecha_fin . 'de' . $datos_usuario_logueado['nombres'] . ' ' . $datos_usuario_logueado['apellidos'];
            $vista = 'exports.reportes.gastos_por_fecha';
            $export_excel = new GastoExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * It takes a request, gets some data from the request, gets some data from the database, and then
     * returns a file
     *
     * @param Request request The request object.
     */
    public function reporteAutorizaciones(Request $request, $tipo)
    {
        try {
            $fecha_inicio =  $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_archivo = $tipo;
            $id_tipo_reporte = $request->tipo_reporte;
            $id_usuario = $request->usuario;
            $usuario = Empleado::where('id', $id_usuario)->first();
            $tipo_reporte = EstadoViatico::where('id', $id_tipo_reporte)->first();
            $reporte = Gasto::with('empleado', 'detalle_info', 'subDetalle', 'tarea')
                ->where('estado', $id_tipo_reporte)
                ->where('aut_especial', $id_usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->get();
            $subtotal = Gasto::with('empleado', 'detalle_info', 'subDetalle')
                ->where('estado', $id_tipo_reporte)
                ->where('aut_especial', $id_usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])->sum('total');
            $reporte_empaquetado = Gasto::empaquetar($reporte);
            $div = $tipo_reporte->nombre == 'Aprobado' ? 10 : 12;
            $resto = 0;
            $DateAndTime = date('Y-m-d H:i:s');
            $reportes =  [
                'div' => $div,
                'resto' => $resto,
                'datos_reporte' => $reporte_empaquetado,
                'tipo_ARCHIVO' => $tipo_archivo,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'usuario' => $usuario,
                'tipo_reporte' => $tipo_reporte,
                'subtotal' => $subtotal,
                'DateAndTime' => $DateAndTime
            ];
            $nombre_reporte = 'reporte_autorizaciones_' . $fecha_inicio . '-' . $fecha_fin;
            $vista = 'exports.reportes.reporte_autorizaciones';
            $export_excel = new AutorizacionesExport($reportes);
            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al aprobar el gasto' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    /**
     * It updates the status of the expense to 1, which means it is approved.
     *
     * @param Request request The request object.
     *
     * @return A JSON object with the success message.
     */
    public function aprobarGasto(GastoRequest $request)
    {
        try {
            DB::beginTransaction();
            $gasto = Gasto::find($request->id);
            $datos = $request->validated();
            GastoService::convertirComprobantesBase64Url($datos, 'update');
            if($gasto){
                $gasto->update($datos);
                $gasto_service = new GastoService($gasto);
                $gasto_service->validarGastoVehiculo($request);
                $gasto_service->sincronizarBeneficiarios($request->beneficiarios);
                event(new FondoRotativoEvent($gasto));
                $gasto_service->marcarNotificacionLeida();
                DB::commit();
            }
            return response()->json(['success' => 'Gasto autorizado correctamente']);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al aprobar gasto' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * It updates the status of the expense to 1, which means it is rejected.
     *
     * @param Request request The request object.
     *
     * @return A JSON object with the success message.
     */
    /**
     * La función `rechazarGasto` en PHP maneja el rechazo de un registro de gastos, actualiza su estado,
     * activa un evento, marca una notificación como leída y revierte la transacción en caso de error.
     *
     * @param Request request La función `rechazarGasto` se utiliza para rechazar un gasto (gasto) en base
     * a los datos de la solicitud proporcionados. Aquí hay un desglose de la función:
     *
     * @return una respuesta JSON con un mensaje de éxito 'Gasto rechazado' si la operación es exitosa. Si
     * ocurre una excepción durante el proceso, detectará la excepción, revertirá la transacción y
     * devolverá una respuesta JSON con un mensaje de error que indica que ocurrió un error al rechazar el
     * gasto junto con el mensaje de excepción y el número de línea.
     */
    public function rechazarGasto(Request $request)
    {
        try {
            DB::beginTransaction();
            $gasto = Gasto::find($request->id);
            $gasto->estado = Gasto::RECHAZADO;
            $gasto->detalle_estado = $request->detalle_estado;
            $gasto->save();
            event(new FondoRotativoEvent($gasto));
            $gasto_service = new GastoService($gasto);
            $gasto_service->marcarNotificacionLeida();
            DB::commit();
            return response()->json(['success' => 'Gasto rechazado']);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al rechazar el gasto' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    /**
     * La función `anularGasto` en PHP se utiliza para marcar un gasto específico como cancelado y manejar
     * cualquier error que pueda ocurrir durante el proceso.
     *
     * @param Request request La función `anularGasto` se utiliza para cancelar un gasto específico (gasto)
     * en función de los datos de la solicitud proporcionados. Aquí hay un desglose de la función:
     *
     * @return una respuesta JSON con un mensaje de éxito 'Gasto rechazado' si la operación es exitosa. Si
     * ocurre un error durante el proceso, arrojará una ValidationException con un mensaje de error 'Error
     * al insertar registro' que contiene el mensaje de excepción. Además devolverá una respuesta JSON con
     * un mensaje de error 'Ha ocurrido un error al rechazar el gasto
     */
    public function anularGasto(Request $request)
    {
        try {
            DB::beginTransaction();
            $gasto = Gasto::where('id', $request->id)->first();
            if ($gasto->estado == Gasto::ANULADO) {
                throw ValidationException::withMessages([
                    '404' => ['El gasto ya fue anulado'],
                ]);
            }
            $gasto->estado = Gasto::ANULADO;
            $gasto->observacion_anulacion = $request->observacion_anulacion;
            $gasto->save();
            event(new FondoRotativoEvent($gasto));
            $gasto_service = new GastoService($gasto);
            $gasto_service->marcarNotificacionLeida();
            DB::commit();
            return response()->json(['success' => 'Gasto rechazado']);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al rechazar el gasto' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }



    /**
     * La función `reporteValoresFondos` recupera valores de fondos basados en la información de los
     * empleados y maneja excepciones con el registro.
     *
     * @param Request request Según el fragmento de código proporcionado, la función `reporteValoresFondos`
     * toma un objeto `Solicitud` como parámetro. Este objeto "Solicitud" probablemente contenga datos
     * enviados desde una solicitud del lado del cliente, como datos de formulario o parámetros de
     * consulta.
     *
     * @return La función `reporteValoresFondos` está devolviendo una respuesta JSON que contiene la
     * variable `resultados`. La variable `resultados` es una matriz que contiene los valores obtenidos al
     * llamar al método `obtenerValoresFondosRotativos` en `EmpleadoService` si `->todos` es
     * verdadero, o contiene el valor obtenido al llamar a `obtenerValoresFond
     */
    public function reporteValoresFondos(Request $request)
    {
        try {
            $empleadoService = new EmpleadoService();
            if ($request->todos)
                $results = $empleadoService->obtenerValoresFondosRotativos();
            else
                $results = [$empleadoService->obtenerValoresFondosRotativosEmpleado(Empleado::find($request->empleado ? $request->empleado : auth()->user()->empleado->id))];
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['ERROR al imprimir el reporte', $th->getMessage(), $th->getLine()]);
            throw ValidationException::withMessages(['error' => [$th->getMessage()]]);
        }
        return response()->json(compact('results'));
    }
}
