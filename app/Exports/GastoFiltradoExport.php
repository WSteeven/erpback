<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class GastoFiltradoExport implements FromView,ShouldAutoSize,WithColumnWidths
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
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 37,
            'C' => 24,
            'D' => 10,
            'E' => 37,
            'F' => 78,
            'G' => 22,
            'H' => 250,
            'I' => 162,
            'J' => 37,
            'K' => 15,
        ];
    }
}
