<?php

namespace App\Exports\FondosRotativos\Saldos;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class AcreditacionSemanalExport implements FromView,ShouldAutoSize,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {
        return view('exports.reportes.excel.acreditacion_semanal',$this->reporte);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 41,
            'C' => 20,
            'D' => 20,
            'E' => 45
        ];
    }
}
