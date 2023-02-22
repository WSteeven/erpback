<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Events\FondoRotativoEvent;
use App\Exports\ViaticoExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\ViaticoResource;
use App\Http\Resources\UserInfoResource;
use App\Models\FondosRotativos\Viatico\DetalleViatico;
use App\Models\FondosRotativos\Viatico\EstadoViatico;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Viatico\Viatico;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Excel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class ViaticoController extends Controller
{
    private $entidad = 'viatico';
    public function __construct()
    {
        $this->middleware('can:puede.ver.fondo')->only('index', 'show');
        $this->middleware('can:puede.crear.fondo')->only('store');
        $this->middleware('can:puede.editar.fondo')->only('update');
        $this->middleware('can:puede.eliminar.fondo')->only('update');
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
        $datos['id_usuario'] = $user->id;
        $datos['fecha_ingreso'] = date('Y-m-d h:i:s');
        $datos['fecha_proc'] = date('Y-m-d h:i:s');
        $dtaos['fecha_trans'] = date('Y-m-d h:i:s');
        $datos['fecha_viat'] = date('Y-m-d', strtotime($request->fecha_viat));
        $datos['transcriptor'] = $user->name;
        $datos['estado'] = $datos_estatus_via->id;
        $datos['cantidad'] = $request->cant;
        $datos['id_tarea'] = $request->num_tarea !== null ? $datos['id_tarea'] = $request->num_tarea : $datos['id_tarea'] = null;
        $datos['id_proyecto'] = $request->proyecto !== 0 ? $datos['id_proyecto'] = $request->proyecto : $datos['id_proyecto'] = null;
        //Convierte base 64 a url
        if ($request->comprobante1 != null) $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante1, RutasStorage::COMPROBANTES_VIATICOS))->execute();
        if ($request->comprobante2 != null) $datos['comprobante2'] = (new GuardarImagenIndividual($request->comprobante2, RutasStorage::COMPROBANTES_VIATICOS))->execute();
        //Guardar Registro
        $modelo = Viatico::create($datos);
        $max_datos_usuario = SaldoGrupo::where('id_usuario', $user->id)->max('id');
        $datos_saldo_usuario = SaldoGrupo::where('id', $max_datos_usuario )->first();
        $saldo_actual_usuario=(float)$datos_saldo_usuario->saldo_actual;
        $modelo = new ViaticoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        //Actualiza saldo
        $saldo_grupo = SaldoGrupo::where('id', $datos_saldo_usuario->id)->first();
        $saldo_grupo->saldo_actual = (float)$saldo_actual_usuario - (float)$saldo_consumido_viatico;
        $saldo_grupo->save();
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
        $datos['fecha_ingreso'] = date('Y-m-d');
        $datos['transcriptor'] = $user->name;
        $datos['estado'] = $datos_estatus_via->id;
        $datos['cantidad'] = $request->cant;

        //Respuesta
        $activo->update($datos);
        $modelo = new ViaticoResource($activo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('modelo', 'mensaje'));
    }

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
    public function generar_reporte(Request $request, $tipo)
    {
        $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
        $usuario_logeado = Auth::user();
        $id_usuario = $usuario_logeado->id;
        $DateAndTime = date('m-d-Y h:i:s a', time());
        $usuario_logeado = User::where('id', $id_usuario)->get();
        $idUsuarioLogeado = $usuario_logeado[0]->id;

        $fecha_titulo_ini = explode("-",  $fecha_inicio);
        $fecha_titulo_fin = explode("-", $fecha_fin);
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

        $datos_saldo_depositados_semana = SaldoGrupo::where('id_usuario', $idUsuarioLogeado)
            ->where('saldo_depositado', '!=', 0)
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
        $total_observacion = "";
        $restas_diferencias = 0;
        $corte = 900;
        if ($diff->days > 6) {
            //////sacando de don inicia la semana para el corte
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

            $datos_saldo_depositados_semana = SaldoGrupo::where('id_usuario', $idUsuarioLogeado)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
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
                ->get();

            $sal_anterior = Count($datos_saldo_anterior) > 0 ? $datos_saldo_anterior->saldo_anterior : 0;
            $sal_dep_r = $datos_fecha_rango[0]->saldo_depositado;

            $restas_diferencias = $diferencia_corte - $diferencia_rango;
            $resta_porcentaje = 8;
            ////fin
        } else {
            $datos_saldo_depositados_semana = SaldoGrupo::where('id_usuario',  $idUsuarioLogeado)
                ->whereRaw("date_format(fecha, '%Y-%m-%d') BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'")
                ->where('saldo_depositado', '<>', 0)
                ->orderByDesc('id')
                ->get();

            $datos_saldo_usuario_depositado = SaldoGrupo::select(DB::raw('SUM(saldo_depositado) as saldo_depositado'))
                ->where('id_usuario', $idUsuarioLogeado)
                ->whereRaw("date_format(fecha, '%Y-%m-%d') BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'")
                ->get();

            $sal_anterior = Count($datos_saldo_usuario_anterior) > 0 ? $datos_saldo_usuario_anterior[0]->saldo_anterior : 0;
            $sal_dep_r = 0;

            foreach ($datos_saldo_depositados_semana as $saldo) {
                $sal_dep_r += $saldo->saldo_depositado;
            }

            $nuevo_saldo = $sal_anterior + $sal_dep_r;
        }
        $usuario_logeado = UserInfoResource::collection($usuario_logeado);
        $sub_total = 0;
        $datos_usuario_logueado =  $this->obtener_usuario($usuario_logeado);
        $reporte = compact(
            'datos_reporte',
            'datos_usuario_logueado',
            'fecha_inicio',
            'fecha_fin',
            'fecha_titulo_ini',
            'fecha_titulo_fin',
            'sal_anterior',
            'sal_dep_r',
            'sub_total',
            'nuevo_saldo',
            'restas_diferencias',
            'datos_saldo_usuario_depositado',
            'DateAndTime',
            'datos_saldo_depositados_semana',
            'datos_saldo_usuario_anterior'
        );
        $nombre_reporte = 'reporte_'.$fecha_inicio.'-'.$fecha_fin.'de'.$datos_usuario_logueado['nombres'].' '.$datos_usuario_logueado['apellidos'];
        switch ($tipo) {
            case 'excel':
                return Excel::download(new ViaticoExport($reporte), $nombre_reporte.'.xlsx');
                break;
            case 'pdf':

                $pdf = Pdf::loadView('exports.reportes.viaticos_por_fecha', $reporte);
                return $pdf->download($nombre_reporte.'.pdf');
                break;
        }
    }
    private function obtener_usuario($usuario)
    {
        $usuario_logeado =  json_decode(json_encode($usuario), true);
        $usuario_logeado = $usuario_logeado[0];
        return $usuario_logeado;
    }
}
