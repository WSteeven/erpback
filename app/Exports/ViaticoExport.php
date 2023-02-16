<?php

namespace App\Exports;

use App\Http\Resources\UserInfoResource;
use App\Models\FondosRotativos\Viatico\SaldoGrupo;
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
        $datos_reporte = Viatico::selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
            ->whereBetween(DB::raw("DATE_FORMAT(fecha_viat, '%Y-%m-%d')"), array($fecha_inicio, $fecha_fin))
            ->where('estado', '=', 1)
            ->where('id_usuario', '=', $idUsuarioLogeado)
            ->get();

        $datos_saldo_usuario_depositado = SaldoGrupo::selectRaw('SUM(saldo_depositado) as saldo_depositado')
            ->where('id_usuario',$idUsuarioLogeado)
            ->where(function ($query) use ($fecha_inicio, $fecha_fin) {
                $query->whereBetween('fecha_inicio', [$fecha_inicio, $fecha_fin])
                    ->orWhereBetween('fecha_fin', [$fecha_inicio, $fecha_fin]);
            })
            ->first();
        $datos_saldo_depositados_semana = SaldoGrupo::select(['*'])
            ->where('id_usuario',$idUsuarioLogeado)
            ->where('saldo_grupo', '!=', 0)
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
            ->limit(1)
            ->get();
            $nuevo_saldo = ((float)$datos_saldo_usuario_anterior[0]->saldo_anterior + (float)$datos_saldo_usuario_depositado[0]->saldo_depositado);
        $sub_total = 0;
        $fi = new \DateTime($fecha_inicio);
        $ff = new \DateTime($fecha_fin);
        $diff = $fi->diff($ff);

        $usuario_logeado = UserInfoResource::collection($usuario_logeado);

        return view('exports.reportes.viaticos_por_fecha', [
            'datos_reporte' => $datos_reporte,
            'datos_usuario_logueado' => $usuario_logeado,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'datos_saldo_usuario_depositado' => $datos_saldo_usuario_depositado,
            'DateAndTime' => $DateAndTime,
            'datos_saldo_depositados_semana' => $datos_saldo_depositados_semana,
            'datos_saldo_usuario_anterior' => $datos_saldo_usuario_anterior
        ]);
    }


    private function obtener_usuario($usuario){
        $usuario_logeado =  json_decode(json_encode($usuario), true);
        $usuario_logeado = $usuario_logeado[0];
        return $usuario_logeado;
    }
}
