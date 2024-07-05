<?php

namespace App\Exports\Medico;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ReporteCuestionarioPisicosocialExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function view(): View
    {
        return view('medico.cuestionario_psicosocial', $this->reporte);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 42,
            'B' => 41,
            'C' => 20,
            'D' => 20,
            'E' => 45
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $textoTitulo = [
            'font' => [
                'color' => [
                    'argb' => 'FFFFFFFF',
                ],
                'bold' => true,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => Color::COLOR_DARKBLUE],
            ],
        ];

        $sheet->getStyle('A1:CT1')->applyFromArray($textoTitulo);
    }
}
