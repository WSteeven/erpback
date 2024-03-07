<?php

namespace App\Exports\Ventas;

use App\Models\ConfiguracionGeneral;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteValoresCobrarExport  implements FromView ,ShouldAutoSize,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {
        return view('ventas.valores_cobrar',$this->reporte);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 12
        ];
    }

}
