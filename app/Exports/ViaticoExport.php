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
        $usuario_logeado = User::where('id', 26)->get();
        $fecha_inicio = $this->fecha_inicio;
        $fecha_fin = $this->fecha_fin;
        $datos_reporte = Viatico::selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
            ->whereBetween(DB::raw("DATE_FORMAT(fecha_viat, '%Y-%m-%d')"), array($fecha_inicio, $fecha_fin))
            ->where('estado', '=', 1)
            ->where('id_usuario', '=', $usuario_logeado[0]->id)
            ->get();

        $saldo_depositado = SaldoGrupo::selectRaw('SUM(saldo_depositado) as saldo_depositado')
            ->where('id_usuario', $usuario_logeado[0]->id)
            ->where(function ($query) use ($fecha_inicio, $fecha_fin) {
                $query->whereBetween('fecha_inicio', [$fecha_inicio, $fecha_fin])
                    ->orWhereBetween('fecha_fin', [$fecha_inicio, $fecha_fin]);
            })
            ->first();
        $usuario_logeado = UserInfoResource::collection($usuario_logeado);
        $usuario_logeado =  json_decode(json_encode($usuario_logeado), true);
        $usuario_logeado = $usuario_logeado[0];
        return view('exports.reportes.viaticos_por_fecha', [
            'datos_reporte' => $datos_reporte,
            'datos_usuario_logueado' => $usuario_logeado,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'saldo_depositado' => $saldo_depositado->saldo_depositado
        ]);
    }
}
