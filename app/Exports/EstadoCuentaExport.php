<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EstadoCuentaExport implements FromView
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {

        return view('exports.reportes.excel.reporte_consolidado.reporte_movimiento_saldo',$this->reporte);
    }
}
