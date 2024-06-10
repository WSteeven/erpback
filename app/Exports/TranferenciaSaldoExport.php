<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class TranferenciaSaldoExport implements FromView,ShouldAutoSize,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function columnWidths(): array
    {
        return [
            'A'=>10,
            'B'=>36,
            'C'=>36,
            'D'=>8,
            'E'=>15,
            'F'=>27,
            'G'=>100,
        ];
    }
    public function view(): View
    {

        return view('exports.reportes.excel.reporte_consolidado.reporte_transferencia_saldo',$this->reporte);
    }
}
