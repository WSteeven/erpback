<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class EstadoCuentaExport implements FromView,ShouldAutoSize,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function columnWidths(): array
    {
        return [
            'A'=>7,
            'B'=>11,
            'C'=>16,
            'D'=>47,
            'E'=>47,
            'F'=>10,
            'G'=>10,
            'H' => 10,
        ];
    }
    public function view(): View
    {
        return view('exports.reportes.excel.reporte_consolidado.reporte_movimiento_saldo',$this->reporte);
    }
}
