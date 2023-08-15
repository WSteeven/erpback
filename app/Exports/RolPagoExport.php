<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RolPagoExport implements FromView
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {
        return view('recursos-humanos.excel.rol_pagos',$this->reporte);
    }
}
