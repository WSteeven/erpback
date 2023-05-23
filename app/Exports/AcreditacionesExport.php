<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AcreditacionesExport implements FromView
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {
        return view('exports.reportes.excel.reporte_consolidado.reporte_acreditaciones_usuario',$this->reporte);
    }
}
