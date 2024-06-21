<?php

namespace App\Exports\Tareas;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// Styles
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Src\App\Tareas\MaterialesUtilizadosTareaService;

class ReporteMaterialUtilizadoExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $reporte;
    protected MaterialesUtilizadosTareaService $service;
    const TOTAL_FILAS_ENCABEZADO = 2;

    function __construct($reporte, $service)
    {
        $this->reporte = $reporte;
        $this->service = $service;
    }

    public function view(): View
    {
        return view('tareas.excel.reporte_material_utilizado', ['reporte' => $this->reporte, 'service' => $this->service]);
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

        $totalFilas = count($this->reporte['todos_materiales']) + self::TOTAL_FILAS_ENCABEZADO;

        $sheet->getStyle('A1:GD2')->applyFromArray($textoTitulo);
        $sheet->getStyle('A1:GD200')->applyFromArray($textCenter);
        $sheet->getStyle('A1:GD' . $totalFilas)->applyFromArray($bordeTabla);
        $sheet->getStyle('A1:GD' . $totalFilas)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:A1000')->getFont()->setBold(true);
    }
}
