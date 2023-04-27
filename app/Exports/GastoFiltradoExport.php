<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GastoFiltradoExport implements FromView
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {

        return view('exports.reportes.excel.reporte_consolidado.reporte_gastos_filtrado',$this->reporte);
    }
}
