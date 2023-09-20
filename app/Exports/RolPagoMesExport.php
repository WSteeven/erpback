<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RolPagoMesExport implements FromView
{
    protected $reporte;
    protected $es_quincena;

    function __construct($reporte, $es_quincena = false)
    {
        $this->reporte = $reporte;
        $this->es_quincena = $es_quincena;
    }
    public function view(): View
    {
        if ($this->es_quincena){
            return view('recursos-humanos.excel.rol_pago_quincena',$this->reporte);

        }
        return view('recursos-humanos.excel.rol_pago_mes',$this->reporte);
    }
}
