<?php

namespace App\Exports\Bodega;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class IndexSheetExport implements WithTitle, WithStyles, WithEvents
{
    public function title(): string
    {
        return 'Índice';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FFFFFF']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'startColor' => ['argb' => '4A90E2'],
                'endColor' => ['argb' => '0879DC'],
            ],
        ]);

        $sheet->getStyle('A2:B2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '0879DC']],
        ]);

        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(90);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->setCellValue('A1', 'ÍNDICE DE CONTENIDO');
                $sheet->mergeCells('A1:B1');
                $sheet->setCellValue('A2', 'Hoja (Haga clic para ir a la hoja)');
                $sheet->setCellValue('B2', 'Descripción');

                // Datos del índice con enlaces
                $links = [
                    ['Egresos', 'Egresos de productos al empleado responsable.'],
                    ['Suma egresos', 'Suma de los egresos de productos al empleado responsable.'],
                    ['Devoluciones', 'Devoluciones realizadas por el empleado responsable.'],
                    ['Suma devoluciones', 'Suma de devoluciones realizadas por el empleado responsable.'],
                    ['Transferencias recibidas', 'Transferencias que recibió el empleado responsable.'],
                    ['Suma transferencias recibidas', 'Suma de las transferencias que recibió el empleado responsable.'],
                    ['Transferencias enviadas', 'Transferencias que envió el empleado responsable.'],
                    ['Suma transferencias enviadas', 'Suma de las transferencias que envió el empleado responsable.'],
                    ['Preingresos', 'Productos preingresados al empleado responsable.'],
                    ['Suma preingresos', 'Suma de preingresos al empleado responsable.'],
                    ['Ocupado en tareas', 'Materiales ocupados en tareas por el empleado responsable.'],
                    ['Suma ocupado en tareas', 'Suma de materiales ocupados en tareas por el empleado responsable.'],
                    ['Stock actual', 'Stock actual calculado a partir de los datos mostrados en este excel.'],
                ];

                $row = 3;
                foreach ($links as $link) {
                    $sheet->setCellValue("A{$row}", $link[0] . ' ⮞');
                    $sheet->setCellValue("B{$row}", $link[1]);
                    $sheet->getCell("A{$row}")->getHyperlink()->setUrl("sheet://'" . $link[0] . "'!A1");

                    $sheet->getStyle("A{$row}")->applyFromArray([
                        'font' => ['color' => ['argb' => 'FFFFFF'], 'bold' => true],
                        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '0073E6']],
                        'borders' => [
                            'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => '005BB5']],
                        ],
                    ]);

                    $sheet->getStyle("B{$row}")->applyFromArray([
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'dddddd']],
                    ]);

                    $sheet->getRowDimension($row)->setRowHeight(20);
                    $row++;
                }
            },
        ];
    }
}
