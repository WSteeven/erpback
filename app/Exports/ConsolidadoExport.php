<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ConsolidadoExport implements FromView, ShouldAutoSize, WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function columnWidths(): array
    {
        return [
            'A'=>25,
            'B'=>15,
            'C'=>15,
            'D'=>15,
            'E'=>15,
            'F'=>15,
            'G'=>15,
            'H'=>15,
        ];
    }
    public function view(): View
    {

        return view('exports.reportes.excel.reporte_consolidado.reporte_consolidado_usuario', $this->reporte);
    }
}
