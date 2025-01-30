<?php

namespace App\Exports\Bodega;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SumaProductosStockExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithStrictNullComparison, WithCustomValueBinder, WithColumnWidths, WithStyles, WithTitle, WithCustomStartCell
{
    use Exportable;

    protected $data;
    protected string $title;
    const TOTAL_FILAS_ENCABEZADO = 1;

    public function __construct($data, $title)
    {
        $this->data = $data;
        $this->title = $title;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data;
    }

    // Definir la celda inicial (donde comienzan los datos)
    public function startCell(): string
    {
        return 'A3'; // Los datos reales comenzarán desde la celda A4
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) && $value > 9999) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return [
            'Producto',
            'Detalle producto',
            'Serial',
            'Propietario',
            'Cantidad',
            'Id detalle producto',
            'Id cliente',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 70,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Agregar un enlace al índice en la celda A1
        $sheet->setCellValue('A1', '⮜ Regresar al índice');
        $sheet->getCell('A1')->getHyperlink()->setUrl("sheet://Índice!A1");
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'], // Blanco
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '0073e6'], // Azul vibrante
            ],
        ]);

        // Fusionar las columnas A-E en la primera fila para que el botón ocupe todo el ancho
        $sheet->mergeCells('A1:E1');

        // Ajustar la altura de la fila 1 para hacer que el botón se vea mejor
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Aplicar estilos a las cabeceras
        $textoTitulo = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '0879DC'], // Azul oscuro
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

        // Aplicar estilo a las cabeceras (fila 3)
        $sheet->getStyle('A3:G3')->applyFromArray($textoTitulo);

        // Obtener la cantidad de filas de datos y definir el inicio de datos en la fila 4
        $totalFilas = count($this->data) + 3; // Datos comienzan en fila 4

        // Aplica alineación y bordes a los datos de la tabla (desde fila 4)
        $sheet->getStyle('A4:G' . $totalFilas)->applyFromArray($textCenter);
        $sheet->getStyle('A4:G' . $totalFilas)->applyFromArray($bordeTabla);

        // Aplicar autoajuste de texto en celdas de datos
        $sheet->getStyle('A4:G' . $totalFilas)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A4:A' . $totalFilas)->getFont()->setBold(true);

        // Activa el filtro automático desde la fila 3 (cabeceras)
        $sheet->setAutoFilter('A3:G3');

        // Estilos para filas intercaladas (comienzan en fila 4)
        $estiloGrisClaro = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'D9D9D9'], // Gris claro
            ],
        ];

        $estiloAzulClaro = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'c5d9f1'], // Azul claro
            ],
        ];

        // Aplica los estilos intercalados a partir de la fila 4
        for ($row = 4; $row <= $totalFilas; $row++) {
            if ($row % 2 === 0) { // Fila par: fondo gris claro
                $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([...$estiloGrisClaro]);
            }

            if ($row % 2 !== 0) { // Fila impar: fondo azul claro solo en columna E
                $sheet->getStyle('E' . $row)->applyFromArray($estiloAzulClaro);
            }
        }
    }
}
