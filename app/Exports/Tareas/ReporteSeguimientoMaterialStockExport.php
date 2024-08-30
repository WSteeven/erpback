<?php

namespace App\Exports\Tareas;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteSeguimientoMaterialStockExport implements FromView, WithStyles, WithTitle, WithColumnWidths, WithBackgroundColor
{
    protected $reporte;
    const TOTAL_FILAS_ENCABEZADO = 1;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }

    public function view(): View
    {
        return view('tareas.excel.reporte_seguimiento_material_stock', ['reporte' => $this->reporte]);
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    public function title(): string
    {
        return 'Seguimiento';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 60,
            'C' => 20,
            'D' => 14,
            'E' => 40,
            'F' => 20,
        ];
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

        $sheet->getStyle('A1:F1')->applyFromArray($textoTitulo);
        $sheet->getStyle('A1:F' . $totalFilas)->applyFromArray($textCenter);
        $sheet->getStyle('A1:F' . $totalFilas)->applyFromArray($bordeTabla);
        $sheet->getStyle('A1:F' . $totalFilas)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:A' . $totalFilas)->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:F1');
    }
}
