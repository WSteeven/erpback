<?php

namespace App\Exports;

use App\Http\Resources\UserInfoResource;
use App\Models\FondosRotativos\Viatico\DetalleViatico;
use App\Models\FondosRotativos\Viatico\SaldoGrupo;
use App\Models\FondosRotativos\Viatico\SubDetalleViatico;
use App\Models\FondosRotativos\Viatico\Viatico;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ViaticoExport implements FromView
{
    protected $fecha_inicio;
    protected $fecha_fin;
    function __construct($fecha_inicio, $fecha_fin)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
    }
    public function view(): View
    {
        // $usuario_logeado = Auth::user();
        $DateAndTime = date('m-d-Y h:i:s a', time());
        $usuario_logeado = User::where('id', 26)->get();
        $fecha_inicio = $this->fecha_inicio;
        $fecha_fin = $this->fecha_fin;
        $idUsuarioLogeado = $usuario_logeado[0]->id;
        $fecha_titulo_ini = explode("-",  $fecha_inicio);
        $fecha_titulo_fin = explode("-", $fecha_fin);
        $datos_reporte = Viatico::selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
            ->whereBetween(DB::raw("DATE_FORMAT(fecha_viat, '%Y-%m-%d')"), array($fecha_inicio, $fecha_fin))
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
        $datos_saldo_depositados_semana = SaldoGrupo::select(['*'])
            ->where('id_usuario', $idUsuarioLogeado)
            ->where('saldo_depositado', '!=', 0)
            ->whereBetween(DB::raw('date_format(fecha, "%Y-%m-%d")'), [$fecha_inicio, $fecha_fin])
            ->orderBy('id', 'DESC')
            ->get();
        // Obtener el ID del usuario logeado


        // Obtener el saldo del usuario correspondiente al periodo anterior
        $datos_saldo_usuario_anterior = SaldoGrupo::select(['*'])
            ->where('id_usuario', $idUsuarioLogeado)
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
        $restas_diferencias =0;
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

            $inicio_semana = Count($datos_semana) >0  ? $datos_semana[0]->fecha_inicio: '';
            $fin_semana = Count($datos_semana) >0 ? $datos_semana[0]->fecha_fin:'';


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

            $diferencia_corte = $datos_depositos_corte[0]->saldo_depositado - $datos_gastos_corte[0]->total;

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

            $sal_anterior = Count($datos_saldo_anterior)>0? $datos_saldo_anterior->saldo_anterior:0;
            $sal_dep_r = $datos_fecha_rango[0]->saldo_depositado;

            $restas_diferencias = $diferencia_corte - $diferencia_rango;
            $resta_porcentaje = 8;
            ////fin
        } else {
            $datos_saldo_depositados_semana = SaldoGrupo::where('id_usuario',  $idUsuarioLogeado)
                ->whereRaw("date_format(fecha, '%Y-%m-%d') BETWEEN '" . $_POST['fecha_inicio'] . "' AND '" . $_POST['fecha_fin'] . "'")
                ->where('saldo_depositado', '<>', 0)
                ->orderByDesc('id')
                ->get();

            $datos_saldo_usuario_depositado = SaldoGrupo::select(DB::raw('SUM(saldo_depositado) as saldo_depositado'))
                ->where('id_usuario', $idUsuarioLogeado)
                ->whereRaw("date_format(fecha, '%Y-%m-%d') BETWEEN '" . $_POST['fecha_inicio'] . "' AND '" . $_POST['fecha_fin'] . "'")
                ->get();

            $sal_anterior = $datos_saldo_usuario_anterior[0]->saldo_anterior;
            $sal_dep_r = 0;

            foreach ($datos_saldo_depositados_semana as $saldo) {
                $sal_dep_r += $saldo->saldo_depositado;
            }

            $nuevo_saldo = $sal_anterior + $sal_dep_r;
        }


        $usuario_logeado = UserInfoResource::collection($usuario_logeado);

        return view('exports.reportes.viaticos_por_fecha', [
            'datos_reporte' => $datos_reporte,
            'datos_usuario_logueado' =>$this->obtener_usuario($usuario_logeado),
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'fecha_titulo_ini' => $fecha_titulo_ini,
            'fecha_titulo_fin' => $fecha_titulo_fin,
            'sal_anterior' => $sal_anterior,
            'sal_dep_r' => $sal_dep_r,
            'nuevo_saldo' => $nuevo_saldo,
            'restas_diferencias' => $restas_diferencias,
            'datos_saldo_usuario_depositado' => $datos_saldo_usuario_depositado,
            'DateAndTime' => $DateAndTime,
            'datos_saldo_depositados_semana' => $datos_saldo_depositados_semana,
            'datos_saldo_usuario_anterior' => $datos_saldo_usuario_anterior
        ]);
    }


    private function obtener_usuario($usuario)
    {
        $usuario_logeado =  json_decode(json_encode($usuario), true);
        $usuario_logeado = $usuario_logeado[0];
        return $usuario_logeado;
    }
}
