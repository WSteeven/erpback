<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Events\FondoRotativoEvent;
use App\Exports\AutorizacionesExport;
use App\Exports\ViaticoExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\ViaticoResource;
use App\Http\Resources\UserInfoResource;
use App\Models\FondosRotativos\Viatico\DetalleViatico;
use App\Models\FondosRotativos\Viatico\EstadoViatico;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Viatico\Viatico;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class ViaticoController extends Controller
{
    private $entidad = 'viatico';
    public function __construct()
    {
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
        $results = [];

        $results = Viatico::ignoreRequest(['campos'])->with('detalle_info', 'aut_especial_user', 'estado_info', 'tarea_info', 'proyecto_info')->filter()->get();
        $results = ViaticoResource::collection($results);

        return response()->json(compact('results'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Viatico  $viatico
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $datos = $request->all();
        $user = Auth::user();
        $usuario_autorizado = User::where('id', $request->aut_especial)->first();
        $datos_detalle = DetalleViatico::where('id', $request->detalle)->first();
        //Asignacion de estatus de viatico
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

        //Adaptacion de foreign keys
        $datos['id_lugar'] = $request->lugar;
        $datos['id_usuario'] = $user->id;
        $datos['fecha_viat'] = date('Y-m-d', strtotime($request->fecha_viat));
        $datos['estado'] = $datos_estatus_via->id;
        $datos['cantidad'] = $request->cant;
        $datos['id_tarea'] = $request->num_tarea !== 0 ? $datos['id_tarea'] = $request->num_tarea : $datos['id_tarea'] = null;
        $datos['id_proyecto'] = $request->proyecto !== 0 ? $datos['id_proyecto'] = $request->proyecto : $datos['id_proyecto'] = null;
        //Convierte base 64 a url
        if ($request->comprobante1 != null) $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante1, RutasStorage::COMPROBANTES_VIATICOS))->execute();
        if ($request->comprobante2 != null) $datos['comprobante2'] = (new GuardarImagenIndividual($request->comprobante2, RutasStorage::COMPROBANTES_VIATICOS))->execute();
        //Guardar Registro
        $modelo = Viatico::create($datos);
        $max_datos_usuario = SaldoGrupo::where('id_usuario', $user->id)->max('id');
        $datos_saldo_usuario = SaldoGrupo::where('id', $max_datos_usuario)->first();
        $saldo_actual_usuario = $datos_saldo_usuario != null ? $datos_saldo_usuario->saldo_actual : 0.0;
        $modelo = new ViaticoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        event(new FondoRotativoEvent($modelo));
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Viatico  $viatico
     * @return \Illuminate\Http\Response
     */
    public function update(Viatico $request, Viatico $activo)
    {
        //Adaptacion de foreign keys
        $datos = $request->all();
        $user = Auth::user();
        $usuario_autorizado = User::where('id', $request->aut_especial)->first();
        $datos_detalle = DetalleViatico::where('id', $request->detalle)->first();
        $saldo_consumido_viatico = 0;
        if ($datos_detalle->descripcion == '') {
            if ($datos_detalle->autorizacion == 'SI') {
                $datos_estatus_via = EstadoViatico::where('descripcion', 'POR APROBAR')->first();
            } else {
                $datos_estatus_via = EstadoViatico::where('descripcion', 'APROBADO')->first();
                $saldo_consumido_viatico = (float)$saldo_consumido_viatico + (float)$request->total;
            }
        } else {
            if ($datos_detalle->autorizacion == 'SI') {
                $datos_estatus_via = EstadoViatico::where('descripcion', 'POR APROBAR')->first();
            } else {
                $datos_estatus_via = EstadoViatico::where('descripcion', 'APROBADO')->first();
                $saldo_consumido_viatico = (float)$saldo_consumido_viatico + (float)$request->total;
            }
        }
        //Adaptacion de foreign keys
        $datos['id_lugar'] = $request->lugar;
        $datos['id_usuario'] = $usuario_autorizado->id;
        $datos['estado'] = $datos_estatus_via->id;
        $datos['cantidad'] = $request->cant;

        //Respuesta
        $activo->update($datos);
        $modelo = new ViaticoResource($activo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * It shows the viatico
     *
     * @param Viatico viatico The model that will be used to retrieve the data.
     *
     * @return A JSON object with the data of the viatico.
     */
    public function show(Viatico $viatico)
    {
        $modelo = new ViaticoResource($viatico);
        return response()->json(compact('modelo'), 200);
    }

    public function destroy(Viatico $viatico)
    {
        $viatico->delete();
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
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $usuario_logeado = Auth::user();
            $id_usuario = $usuario_logeado->id;
            $usuario_logeado = User::where('id', $id_usuario)->get();
            $idUsuarioLogeado = $usuario_logeado[0]->id;
            $datos_reporte = Viatico::selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
                ->whereBetween(DB::raw('date_format(fecha_viat, "%Y-%m-%d")'), [$fecha_inicio, $fecha_fin])
                ->where('estado', '=', 1)
                ->where('id_usuario', '=', $idUsuarioLogeado)
                ->get();
            $datos_saldo_usuario_depositado = SaldoGrupo::selectRaw('SUM(saldo_depositado) as saldo_depositado')
                ->where('id_usuario', $idUsuarioLogeado)
                ->where(function ($query) use ($fecha_inicio, $fecha_fin) {
                    $query->whereBetween('fecha_inicio', [$fecha_inicio, $fecha_fin])
                        ->orWhereBetween('fecha_fin', [$fecha_inicio, $fecha_fin]);
                })
                ->get();
            $datos_saldo_depositados_semana = Acreditaciones::with('tipo_fondo', 'tipo_saldo')->where('id_usuario', $idUsuarioLogeado)
                ->where('monto', '!=', 0)
                ->whereBetween(DB::raw('date_format(fecha, "%Y-%m-%d")'), [$fecha_inicio, $fecha_fin])
                ->orderBy('id', 'DESC')
                ->get();
            // Obtener el saldo del usuario correspondiente al periodo anterior
            $datos_saldo_usuario_anterior = SaldoGrupo::where('id_usuario', $idUsuarioLogeado)
                ->where('fecha_inicio', $fecha_inicio)
                ->where('fecha_fin', $fecha_fin)
                ->orderBy('id', 'DESC')
                ->get();
            $nuevo_saldo = ((float)(Count($datos_saldo_usuario_anterior) > 0 ? $datos_saldo_usuario_anterior[0]->saldo_anterior : 0) + (float)(Count($datos_saldo_usuario_depositado) > 0 ? $datos_saldo_usuario_depositado[0]->saldo_depositado : 0));
            $sub_total = 0;
            $fi = new \DateTime($fecha_inicio);
            $ff = new \DateTime($fecha_fin);
            $diff = $fi->diff($ff);
            $restas_diferencias = 0;
            if ($diff->days > 6) {
                $datos_semana = SaldoGrupo::where('id_usuario', $idUsuarioLogeado)
                    ->where('fecha', '<=', $fecha_inicio)
                    ->orderBy('id', 'desc')
                    ->get();
                if (sizeof($datos_semana) == 0) {
                    $datos_semana = SaldoGrupo::where('id_usuario', $idUsuarioLogeado)
                        ->orderBy('id', 'desc')
                        ->get();
                }
                $inicio_semana = Count($datos_semana) > 0  ? $datos_semana[0]->fecha_inicio : '';
                $fin_semana = Count($datos_semana) > 0 ? $datos_semana[0]->fecha_fin : '';
                $datos_depositos_corte =  SaldoGrupo::selectRaw("SUM(saldo_depositado) as saldo_depositado")
                    ->where('id_usuario', $idUsuarioLogeado)
                    ->where('fecha', '>=', $inicio_semana)
                    ->get();
                $datos_gastos_corte = Viatico::select(DB::raw('SUM(total) as total'))
                    ->where('id_usuario', $idUsuarioLogeado)
                    ->where('fecha_viat', '>=', $inicio_semana)
                    ->where('estado', 1)
                    ->get();
                $saldo_depositado = Count($datos_depositos_corte) > 0 ? $datos_depositos_corte[0]->saldo_depositado : 0;
                $total_gastos = Count($datos_gastos_corte) > 0 ? $datos_gastos_corte[0]->total : 0;
                $diferencia_corte =  $saldo_depositado - $total_gastos;
                $datos_fecha_rango = SaldoGrupo::select(DB::raw('SUM(saldo_depositado) as saldo_depositado'))
                    ->where('id_usuario', $idUsuarioLogeado)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
                $datos_rango_gastos = Viatico::select(DB::raw('SUM(total) as total'))
                    ->where('id_usuario', $idUsuarioLogeado)
                    ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                    ->where('estado', 1)
                    ->get();
                $diferencia_rango = $datos_fecha_rango[0]->saldo_depositado - $datos_rango_gastos[0]->total;
                $datos_saldo_anterior = SaldoGrupo::where('id_usuario', $idUsuarioLogeado)
                    ->whereBetween('fecha', [$inicio_semana, $fin_semana])
                    ->orderBy('id', 'desc')
                    ->first();
                $sal_anterior = $datos_saldo_anterior != null ? $datos_saldo_anterior->saldo_anterior : 0;
                $sal_dep_r = $datos_fecha_rango[0]->saldo_depositado;
                $restas_diferencias = $diferencia_corte - $diferencia_rango;
            } else {
                $datos_saldo_anterior = SaldoGrupo::where('id_usuario', $idUsuarioLogeado)
                    ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                    ->orderBy('id', 'desc')
                    ->first();
                $sal_anterior = $datos_saldo_anterior != null ? $datos_saldo_anterior->saldo_anterior : 0;
                $sal_dep_r = Acreditaciones::where('id_usuario', $idUsuarioLogeado)
                    ->whereBetween(DB::raw('date_format(fecha, "%Y-%m-%d")'), [$fecha_inicio, $fecha_fin])
                    ->orderBy('id', 'DESC')
                    ->sum('monto');
                $nuevo_saldo = $sal_anterior + $sal_dep_r;
            }
            $usuario_logeado = UserInfoResource::collection($usuario_logeado);
            $sub_total = 0;
            $datos_usuario_logueado =  $this->obtener_usuario($usuario_logeado);
            $reporte = compact(
                'fecha_inicio',
                'fecha_fin',
                'datos_usuario_logueado',
                'datos_saldo_depositados_semana',
                'sal_anterior',
                'sal_dep_r',
                'nuevo_saldo',
                'sub_total',
                'restas_diferencias',
                'datos_saldo_anterior',
                'datos_reporte',
            );
            Log::channel('testing')->info('Log', ['variable que se envia a la vista', $reporte]);
            $nombre_reporte = 'reporte_' . $fecha_inicio . '-' . $fecha_fin . 'de' . $datos_usuario_logueado['nombres'] . ' ' . $datos_usuario_logueado['apellidos'];
            switch ($tipo) {
                case 'excel':
                    return Excel::download(new ViaticoExport($reporte), $nombre_reporte . '.xlsx');
                    break;
                case 'pdf':

                    $pdf = Pdf::loadView('exports.reportes.viaticos_por_fecha', $reporte);
                    return $pdf->download($nombre_reporte . '.pdf');
                    break;
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
    /**
     * It takes a user object and returns an array of the user's data
     *
     * @param usuario The user's email address.
     *
     * @return The user object is being returned.
     */
    private function obtener_usuario($usuario)
    {
        $usuario_logeado =  json_decode(json_encode($usuario), true);
        $usuario_logeado = $usuario_logeado[0];
        return $usuario_logeado;
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
            $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
            $tipo_ARCHIVO = $tipo;
            $id_tipo_reporte = $request->tipo_reporte;
            $id_usuario = $request->usuario;
            $usuario = User::where('id', $id_usuario)->first();
            $tipo_reporte = EstadoViatico::where('id', $id_tipo_reporte)->first();
            $reporte = Viatico::with('usuario_info', 'detalle_info', 'sub_detalle_info')
                ->where('estado', $id_tipo_reporte)
                ->where('aut_especial', $id_usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])
                ->get();
            $subtotal = Viatico::with('usuario_info', 'detalle_info', 'sub_detalle_info')
                ->where('estado', $id_tipo_reporte)
                ->where('aut_especial', $id_usuario)
                ->whereBetween('fecha_viat', [$fecha_inicio, $fecha_fin])->sum('total');
            $reporte_empaquetado = Viatico::empaquetar($reporte);
            $div = $tipo_reporte->nombre=='Aprobado' ?10 :12;
            $resto =0;
            $DateAndTime = date('Y-m-d H:i:s');
            $reportes =  [
                'div'=>$div,
                'resto'=>$resto,
                'datos_reporte' => $reporte_empaquetado,
                'tipo_ARCHIVO' => $tipo_ARCHIVO,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'usuario' => $usuario,
                'tipo_reporte' => $tipo_reporte,
                'subtotal' => $subtotal,
                'DateAndTime' => $DateAndTime

            ];
            Log::channel('testing')->info('Log', ['variable que se envia a la vista', $reportes]);
            $nombre_reporte = 'reporte_autorizaciones_' . $fecha_inicio . '-' . $fecha_fin;
            switch ($tipo_ARCHIVO) {
                case 'excel':
                    return Excel::download(new AutorizacionesExport($reportes), $nombre_reporte . '.xlsx');
                    break;
                case 'pdf':
                    $pdf = Pdf::loadView('exports.reportes.reporte_autorizaciones', $reportes);
                    return $pdf->download($nombre_reporte . '.pdf');
                    break;
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
}
