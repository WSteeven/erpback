<?php

namespace App\Exports\Medico;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReporteCuestionarioPisicosocialExport implements FromView,ShouldAutoSize,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {
        return view('medico.cuestionario_psicosocial',$this->reporte);
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
