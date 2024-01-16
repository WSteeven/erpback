<?php

namespace App\Exports\RecursosHumanos\Alimentacion;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class AlimentacionExport implements FromView,ShouldAutoSize,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {
        return view('recursos-humanos.excel.reporte_alimentacion',$this->reporte);
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
