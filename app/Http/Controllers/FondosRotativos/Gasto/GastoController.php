<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Events\FondoRotativoEvent;
use App\Exports\AutorizacionesExport;
use App\Exports\GastoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\GastoRequest;
use App\Http\Requests\GastoVehiculoRequest;
use App\Http\Resources\FondosRotativos\Gastos\GastoResource;
use App\Http\Resources\FondosRotativos\Gastos\GastoVehiculoResource;
use App\Http\Resources\UserInfoResource;
use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\BeneficiarioGasto;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Gasto\GastoVehiculo;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Notificacion;
use App\Models\User;
use App\Models\Vehiculos\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use PgSql\Lob;
use Src\App\EmpleadoService;
use Src\App\FondosRotativos\GastoService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\Config\RutasStorage;
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
        $this->middleware('can:puede.eliminar.gasto')->only('update');
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
            $results = Gasto::ignoreRequest(['campos'])->with('detalle_info', 'sub_detalle_info', 'aut_especial_user', 'estado_info', 'tarea_info', 'proyecto_info')->where('fecha_viat', '>=', $fechaViatico)->filter()->get();
            $results = GastoResource::collection($results);
            return response()->json(compact('results'));
        } else {
            $usuario = Auth::user()->empleado;
            $results = Gasto::where('id_usuario', $usuario->id)->orwhere('aut_especial', $usuario->id)->ignoreRequest(['campos'])->with('detalle_info', 'sub_detalle_info', 'aut_especial_user', 'estado_info', 'tarea_info', 'proyecto_info')->filter()->get();
            $results = GastoResource::collection($results);
            return response()->json(compact('results'));
        }
    }
    public function autorizaciones_gastos(Request $request)
    {
        try {
            $usuario_autenticado =  Auth::user();
            $results = [];
            if (!$usuario_autenticado->hasRole('ADMINISTRADOR')) {
                $results = Gasto::where('aut_especial', $usuario_autenticado->empleado->id)->ignoreRequest(['campos'])->with('detalle_info', 'aut_especial_user', 'estado_info', 'tarea_info', 'proyecto_info')->filter()->get();
                $results = GastoResource::collection($results);
                return response()->json(compact('results'));
            } else {
                $fechaActual = Carbon::now();
                $fechaViatico = $fechaActual->subMonths(6)->format('Y-m-d');
                $results = Gasto::ignoreRequest(['campos'])->with('detalle_info', 'sub_detalle_info', 'aut_especial_user', 'estado_info', 'tarea_info', 'proyecto_info')->where('fecha_viat', '>=', $fechaViatico)->filter()->get();
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
            //Adaptacion de foreign keys
            $datos['aut_especial'] =  $request->safe()->only(['aut_especial'])['aut_especial'];
            $datos['id_usuario'] = Auth::user()->empleado->id;
            //Asignacion de estatus de gasto
            $datos_detalle = DetalleViatico::where('id', $request->detalle)->first();
            if ($datos_detalle->descripcion == '') {
                if ($datos_detalle->autorizacion == 'SI') {
                    $datos_estatus_via = EstadoViatico::where('descripcion', 'POR APROBAR')->first();
                } else {
                    $datos_estatus_via = EstadoViatico::where('descripcion', 'APROBADO')->first();
                }
            } else {
                if ($datos_detalle->autorizacion == 'SI') {
                    $datos_estatus_via = EstadoViatico::where('descripcion', 'POR APROBAR')->first();
                } else {
                    $datos_estatus_via = EstadoViatico::where('descripcion', 'APROBADO')->first();
                }
            }
            $datos['estado'] = $datos_estatus_via->id;
            //Convierte base 64 a url
            if ($datos['comprobante']) {
                $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante, RutasStorage::COMPROBANTES_GASTOS))->execute();
            }
            if ($datos['comprobante2']) {
                $datos['comprobante2'] = (new GuardarImagenIndividual($request->comprobante2, RutasStorage::COMPROBANTES_GASTOS))->execute();
            }
            $bloqueo_comprobante_aprob = Gasto::where('num_comprobante', '!=', null)
                ->where('num_comprobante',  $datos['num_comprobante'])
                ->where('estado', 1)
                ->lockForUpdate()
                ->get();
            if (count($bloqueo_comprobante_aprob) > 0) {
                throw ValidationException::withMessages([
                    '404' => ['comprobante  ya existe'],
                ]);
            }
            $bloqueo_gastos_aprob = DB::table('gastos')
                ->where('ruc', '=', $datos['ruc'])
                ->where('ruc', '!=', '9999999999999')
                ->where('factura', '=', $datos['factura'])
                ->where('estado', '=', 1)
                ->lockForUpdate()
                ->get();
            if (count($bloqueo_gastos_aprob) > 0) {
                throw ValidationException::withMessages([
                    '404' => ['factura ya existe'],
                ]);
            }
            $bloqueo_comprobante_pendiente = Gasto::where('num_comprobante', '!=', null)
                ->where('num_comprobante',  $datos['num_comprobante'])
                ->where('estado', 3)
                ->lockForUpdate()
                ->get();
            if (count($bloqueo_comprobante_pendiente) > 0) {
                throw ValidationException::withMessages([
                    '404' => ['comprobante  ya existe'],
                ]);
            }
            $bloqueo_gastos_pend = DB::table('gastos')
                ->where('ruc', '=', $datos['ruc'])
                ->where('ruc', '!=', '9999999999999')
                ->where('factura', '=', $datos['factura'])
                ->where('estado', '=', 3)
                ->lockForUpdate()
                ->get();
            if (count($bloqueo_gastos_pend) > 0) {
                throw ValidationException::withMessages([
                    '404' => ['factura ya existe'],
                ]);
            }

            //Guardar Registro
            $gasto = Gasto::create($datos);
            $modelo = new GastoResource($gasto);
            //Guardar en tabla de destalle gasto
            $gasto->sub_detalle_info()->sync($request->sub_detalle);
            if ($request->beneficiarios != null) {
                $this->crear_beneficiarios($gasto, $request->beneficiarios);
            }
            $datos['id_gasto'] = $gasto->id;
            //Busca si existe detalle de gasto 6 o 16

            if ($request->detalle == 24) {
                $this->guardar_gasto_vehiculo($request, $gasto);
            }
            if ($request->detalle == 6 || $request->detalle == 16) {
                //busca en arreglo sub_detalle si existe el id 65, 66,96 y 97
                $sub_detalle = $request->sub_detalle;
                $sub_detalle = array_map('intval', $sub_detalle);
                $sub_detalle = array_flip($sub_detalle);
                if (array_key_exists(65, $sub_detalle)) {
                    $this->guardar_gasto_vehiculo($request, $gasto);
                }
                if (array_key_exists(66, $sub_detalle)) {
                    $this->guardar_gasto_vehiculo($request, $gasto);
                }
                if (array_key_exists(97, $sub_detalle)) {
                    $this->guardar_gasto_vehiculo($request, $gasto);
                }
                if (array_key_exists(96, $sub_detalle)) {
                    $this->guardar_gasto_vehiculo($request, $gasto);
                }
                if (array_key_exists(97, $sub_detalle)) {
                    $this->guardar_gasto_vehiculo($request, $gasto);
                }
            }
            event(new FondoRotativoEvent($gasto));
            $max_datos_usuario = SaldoGrupo::where('id_usuario', auth()->user()->id)->max('id');
            $datos_saldo_usuario = SaldoGrupo::where('id', $max_datos_usuario)->first();
            $saldo_actual_usuario = $datos_saldo_usuario != null ? $datos_saldo_usuario->saldo_actual : 0.0;
            $modelo = new GastoResource($modelo);
            DB::table('gastos')
                ->where('ruc', '=', $datos['ruc'])
                ->where('factura', '=', $datos['factura'])
                ->where('num_comprobante', '=', $datos['num_comprobante'])
                ->where('estado', '=', 1)
                ->sharedLock()
                ->get();
            DB::table('gastos')
                ->where('ruc', '=', $datos['ruc'])
                ->where('factura', '=', $datos['factura'])
                ->where('num_comprobante', '=', $datos['num_comprobante'])
                ->where('estado', '=', 3)
                ->sharedLock()
                ->get();
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
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
    public function generar_reporte(Request $request, $tipo)
    {
        try {
            $datos_usuario_logueado = $request->usuario == null ? Empleado::where('id', Auth::user()->empleado->id)->first() : Empleado::where('id', $request->usuario)->first();
            $date_inicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $fecha_anterior = date('Y-m-d', strtotime($fecha_inicio . '- 1 day'));
            $saldo_anterior_data = SaldoGrupo::where('id_usuario', $datos_usuario_logueado->id)
                ->where('fecha', $fecha_anterior)
                ->first();
            $saldo_anterior = $saldo_anterior_data != null ? $saldo_anterior_data->saldo_actual : 0.0;
            $acreditaciones = Acreditaciones::with('usuario')
                ->where('id_usuario', $datos_usuario_logueado->id)
                ->where('id_estado', EstadoAcreditaciones::REALIZADO)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $gastos_realizados = Gasto::with('empleado_info', 'detalle_estado', 'sub_detalle_info')
                ->where('estado', 1)
                ->where('id_usuario', $datos_usuario_logueado->id)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->sum('total');
            $gastos_reporte = Gasto::with('empleado_info', 'detalle_info', 'sub_detalle_info', 'aut_especial_user')->selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->where('estado', '=', 1)
                ->where('id_usuario', '=',  $datos_usuario_logueado->id)
                ->get();

            $transferencias_enviadas = Transferencias::where('usuario_envia_id',  $datos_usuario_logueado->id)
                ->with('usuario_recibe', 'usuario_envia')
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $transferencia_enviada = Transferencias::where('usuario_envia_id', $datos_usuario_logueado->id)
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $transferencia_recibida = Transferencias::where('usuario_recibe_id', $datos_usuario_logueado->id)
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->sum('monto');
            $transferencias_recibidas = Transferencias::where('usuario_recibe_id', $datos_usuario_logueado->id)
                ->with('usuario_recibe', 'usuario_envia')
                ->where('estado', 1)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->get();
            $ultimo_saldo = SaldoGrupo::where('id_usuario', $datos_usuario_logueado->id)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->orderBy('id', 'desc')
                ->first();
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
    public function reporte_autorizaciones(Request $request, $tipo)
    {
        try {
            $date_inicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio);
            $date_fin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin);
            $fecha_inicio = $date_inicio->format('Y-m-d');
            $fecha_fin = $date_fin->format('Y-m-d');
            $tipo_ARCHIVO = $tipo;
            $id_tipo_reporte = $request->tipo_reporte;
            $id_usuario = $request->usuario;
            $usuario = Empleado::where('id', $id_usuario)->first();
            $tipo_reporte = EstadoViatico::where('id', $id_tipo_reporte)->first();
            $reporte = Gasto::with('empleado_info', 'detalle_info', 'sub_detalle_info', 'tarea_info')
                ->where('estado', $id_tipo_reporte)
                ->where('aut_especial', $id_usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->get();
            $subtotal = Gasto::with('empleado_info', 'detalle_info', 'sub_detalle_info')
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
                'tipo_ARCHIVO' => $tipo_ARCHIVO,
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
    public function aprobar_gasto(GastoRequest $request)
    {
        try {
            DB::beginTransaction();
            $gasto = Gasto::find($request->id);
            $datos = $request->validated();
            $datos['estado'] = 1;
            if ($datos['comprobante'] && Utils::esBase64($datos['comprobante'])) {
                $datos['comprobante'] = (new GuardarImagenIndividual($datos['comprobante'], RutasStorage::COMPROBANTES_GASTOS))->execute();
            } else {
                unset($datos['comprobante']);
            }
            if ($datos['comprobante2'] && Utils::esBase64($datos['comprobante2'])) {
                $datos['comprobante2'] = (new GuardarImagenIndividual($datos['comprobante2'], RutasStorage::COMPROBANTES_GASTOS))->execute();
            } else {
                unset($datos['comprobante2']);
            }
            $gasto->update($datos);
            event(new FondoRotativoEvent($gasto));
            $gasto_service = new GastoService($gasto);
            $gasto_service->marcar_notificacion_leida();
            DB::commit();
            return response()->json(['success' => 'Gasto autorizado correctamente']);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al aprobar gasto' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al aprobar el gasto' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    /**
     * It updates the status of the expense to 1, which means it is rejected.
     *
     * @param Request request The request object.
     *
     * @return A JSON object with the success message.
     */
    public function rechazar_gasto(Request $request)
    {
        try {
            DB::beginTransaction();
            $gasto = Gasto::where('id', $request->id)->first();
            $gasto->estado = 2;
            $gasto->detalle_estado = $request->detalle_estado;
            $gasto->save();
            event(new FondoRotativoEvent($gasto));
            $gasto_service = new GastoService($gasto);
            $gasto_service->marcar_notificacion_leida();
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
    public function anular_gasto(Request $request)
    {
        try {
            DB::beginTransaction();
            $gasto = Gasto::where('id', $request->id)->first();
            if ($gasto->estado == 4) {
                throw ValidationException::withMessages([
                    '404' => ['El gasto ya fue anulado'],
                ]);
            }
            $gasto->estado = 4;
            $gasto->detalle_estado = $request->detalle_estado;
            $gasto->save();
            event(new FondoRotativoEvent($gasto));
            $gasto_service = new GastoService($gasto);
            $gasto_service->marcar_notificacion_leida();
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
    public function crear_beneficiarios(Gasto $gasto, $beneficiarios)
    {
        $beneficiariosActualizados = array();

        foreach ($beneficiarios as $empleado_id) {
            $nuevoElemento = array(
                'gasto_id' =>  $gasto->id,
                'empleado_id' => $empleado_id
            );
            $beneficiariosActualizados[] = $nuevoElemento;
        }
        BeneficiarioGasto::insert($beneficiariosActualizados);
    }
    public function guardar_gasto_vehiculo(GastoRequest $request, Gasto $gasto)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $datos['id_gasto'] = $gasto->id;
            $datos['id_vehiculo'] = $request->vehiculo == 0 ? null : $request->safe()->only(['vehiculo'])['vehiculo'];
            $datos['placa'] = Vehiculo::where('id', $datos['id_vehiculo'])->first()->placa;
            $gasto_vehiculo =  GastoVehiculo::create($datos);
            $modelo = new GastoVehiculoResource($gasto_vehiculo);
            DB::table('gasto_vehiculos')->where('id_gasto', '=', $gasto->id)->sharedLock()->get();
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages(['error' => [$e->getMessage()]]);
        }
    }


    public function reporte_valores_fondos(Request $request)
    {
        try {
            Log::channel('testing')->info('Log', ['Request', $request->all()]);
            $empleadoService = new EmpleadoService();
            $results = $empleadoService->obtenerValoresFondosRotativos();
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['ERROR al imprimir el reporte', $th->getMessage(), $th->getLine()]);
            throw ValidationException::withMessages(['error' => [$th->getMessage()]]);
        }
        return response()->json(compact('results'));
    }
}
