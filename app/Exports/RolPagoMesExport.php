<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RolPagoMesExport implements FromView,ShouldAutoSize,WithStyles,WithColumnWidths
{
    protected $reporte;
    protected $es_quincena;

    function __construct($reporte, $es_quincena = false)
    {
        $this->reporte = $reporte;
        $this->es_quincena = $es_quincena;
    }
    public function view(): View
    {
        if ($this->es_quincena){
            return view('recursos-humanos.excel.rol_pago_quincena',$this->reporte);

        }
        return view('recursos-humanos.excel.rol_pago_mes',$this->reporte);
    }
    public function styles(Worksheet $sheet)
    {
        $columns = ['A', 'B', 'C','D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'S'];
        foreach ($columns as $column) {
            $sheet->getStyle($column)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
    }
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 39,
        ];
    }
}
