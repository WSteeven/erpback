<?php

namespace App\Exports\Tareas;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ReporteMaterialExport implements FromView, WithStyles, WithTitle, WithColumnWidths, WithBackgroundColor
{
    protected $reporte;
    const TOTAL_FILAS_ENCABEZADO = 1;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    public function title(): string
    {
        return 'Reporte de materiales';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 60,
            'C' => 14,
            'D' => 14,
            'E' => 25,
            'F' => 20,
            'G' => 60,
            'H' => 50,
            'I' => 25,
            'J' => 25,
            'K' => 25,
        ];
    }

    public function view(): View
    {
        return view('tareas.excel.reporte_material', ['reporte' => $this->reporte]);
    }

    public function styles(Worksheet $sheet)
    {
        $textoTitulo = [
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => '000000',
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $textCenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $bordeTabla = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $totalFilas = count($this->reporte) + self::TOTAL_FILAS_ENCABEZADO;

        $sheet->getStyle('A1:J1')->applyFromArray($textoTitulo);
        $sheet->getStyle('A1:J' . $totalFilas)->applyFromArray($textCenter);
        $sheet->getStyle('A1:J' . $totalFilas)->applyFromArray($bordeTabla);
        $sheet->getStyle('A1:J' . $totalFilas)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:A' . $totalFilas)->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:K1');
    }
}
