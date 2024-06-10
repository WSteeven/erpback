<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SaldoActualExport implements FromView,ShouldAutoSize,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function columnWidths(): array
    {
        return [
            'A'=>8,
            'B'=>44,
            'C'=>57,
            'D'=>24,
            'E'=>8,
        ];
    }
    public function view(): View
    {

        return view('exports.reportes.excel.reporte_saldo_actual',$this->reporte);
    }
}
