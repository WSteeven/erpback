<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class AcreditacionesExport implements FromView,ShouldAutoSize,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function columnWidths(): array
    {
        return [
            'A'=>4,
            'B'=>60,
            'C'=>10,
            'D'=>11,
            'E'=>34,
            'F'=>7,
        ];
    }
    public function view(): View
    {
        return view('exports.reportes.excel.reporte_consolidado.reporte_acreditaciones_usuario',$this->reporte);
    }
}
