<?php

namespace App\Exports\Medico;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReporteCuestionarioAlcoholDrogasExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles, WithDrawings
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }

    public function view(): View
    {
        return view('medico.cuestionario_alcohol_drogas', $this->reporte);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 23,
            'C' => 15,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 18,
            'H' => 13,
            'I' => 12,
            'J' => 25,
            'K' => 10,
            'L' => 21,
            'M' => 20,
            'N' => 20,
            'O' => 20,
            'P' => 20,
            'Q' => 21,
            'R' => 22,
            'S' => 20,
            'T' => 20,
            'U' => 24,
            'V' => 32,
            'W' => 32,
            'X' => 25,
            'Y' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        /* $cantidad_filas = 100;

        for ($columna = 1; $columna <= $cantidad_filas; $columna++) {
            $sheet->getStyle('F' . $columna)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        } */
        // $sheet->setFontSize('F1', 12);
        /* $styleArray = [
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => 'FFA0A0A0',
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ]; */
        $textoTitulo = [
            'font' => [
                'color' => [
                    'argb' => 'FFFFFFFF',
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

        $bordes = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'top' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
            ],
        ];

        $sheet->getStyle('A1:Y200')->applyFromArray($bordes);

        $sheet->getStyle('A3:Y3')->applyFromArray($textoTitulo);
        $sheet->getStyle('A1:Y200')->applyFromArray($textCenter);
        $sheet->getStyle('A1:Y200')->getAlignment()->setWrapText(true);
        $sheet->mergeCells('A1:Y1');
        $sheet->mergeCells('A2:Y2');
        
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->setCellValue('A2', 'DIAGNÓSTICO INICIAL PROGRAMA INTEGRAL DE REDUCCIÓN Y PREVENCIÓN DEL USO Y CONSUMO DE DROGAS EN EMPRESAS E INSTITUCIONES PÚBLICAS Y PRIVADAS');
        // $sheet->getStyle('A1:Y1')-> // setRowHeight(100);
        // $sheet->setRowHeight(15);
        // $sheet->getStyle('A1:Y1')->applyFromArray($styleArrayA1);
    }

    // C:\laragon\www\backend_jpconstrucred\public\img\alcohol_drogas
    public function drawings()
    {
        $drawing1 = new Drawing();
        $drawing1->setName('Logo');
        $drawing1->setDescription('This is my logo');
        $drawing1->setPath(public_path('/img/alcohol_drogas/secretaria_tecnica_drogas_logo.png'));
        $drawing1->setHeight(50);
        $drawing1->setCoordinates('A1');

        $drawing2 = new Drawing();
        $drawing2->setName('Logo');
        $drawing2->setDescription('This is my logo');
        $drawing2->setPath(public_path('/img/alcohol_drogas/ministerio_trabajo_logo.png'));
        $drawing2->setHeight(80);
        $drawing2->setCoordinates('H1');

        $drawing3 = new Drawing();
        $drawing3->setName('Logo');
        $drawing3->setDescription('This is my logo');
        $drawing3->setPath(public_path('/img/alcohol_drogas/ministerio_salud_publica.png'));
        $drawing3->setHeight(50);
        $drawing3->setCoordinates('X1');

        return [$drawing1, $drawing2, $drawing3];
    }
}
