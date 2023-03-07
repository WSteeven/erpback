<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AutorizacionesExport implements FromView
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {

        return view('exports.reportes.reporte_autorizaciones',$this->reporte);
    }
}
