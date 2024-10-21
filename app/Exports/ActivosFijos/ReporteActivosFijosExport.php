<?php

namespace App\Exports\ActivosFijos;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Contracts\View\View;

class ReporteActivosFijosExport implements FromView, WithStyles, WithTitle, WithColumnWidths, WithBackgroundColor
{
    protected $reporte;
    protected $title;
    const TOTAL_FILAS_ENCABEZADO = 2;

    function __construct($reporte, $title)
    {
        $this->reporte = $reporte;
        $this->title = $title;
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 60,
            'C' => 30,
            'D' => 20,
            'E' => 20,
            'F' => 25,
            'G' => 25,
            'H' => 25,
            'I' => 30,
            'J' => 25,
            'K' => 30,
            'L' => 25,
            'M' => 25,
        ];
    }

    public function view(): View
    {
        return view('activos_fijos.excel.activos_fijos', ['reporte' => $this->reporte]);
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

        $sheet->getStyle('A1:M1')->applyFromArray($textoTitulo);
        $sheet->getStyle('A1:M' . $totalFilas)->applyFromArray($textCenter);
        $sheet->getStyle('A1:M' . $totalFilas)->applyFromArray($bordeTabla);
        $sheet->getStyle('A1:M' . $totalFilas)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:A' . $totalFilas)->getFont()->setBold(true);
        $sheet->getStyle('D2:M2')->getFont()->setBold(true);
        $sheet->setAutoFilter('A2:M2');

        for ($fila = 3; $fila <= $totalFilas; $fila++) {
            $sheet->getStyle('E' . $fila)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        }

        for ($fila = 3; $fila <= $totalFilas; $fila++) {
            $sheet->getStyle('J' . $fila)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        }
    }
}
