<?php

namespace App\Exports;

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
 function __construct($fecha_inicio, $fecha_fin) {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
 }
    public function view(): View
    {
       // $usuario_logeado = Auth::user();
       $usuario_logeado = User::where('id', 26)->first();
        $datos_reporte = Viatico::selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
        ->whereBetween(DB::raw("DATE_FORMAT(fecha_viat, '%Y-%m-%d')"), array($this->fecha_inicio, $this->fecha_fin))
        ->where('estado', '=', 1)
        ->where('id_usuario', '=', $usuario_logeado->id)
        ->get();
        return view('exports.reportes.viaticos_por_fecha', [
            'viaticos' => $datos_reporte,
            'datos_usuario_logueado' => $usuario_logeado
        ]);
    }
}
