<?php

namespace App\Exports;

use App\Models\TransaccionBodega;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaccionBodegaIngresoExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithStrictNullComparison, WithCustomValueBinder, WithStyles, WithTitle, WithColumnWidths
{
    use Exportable;

    protected $data;
    protected string $title;
    const TOTAL_FILAS_ENCABEZADO = 1;

    function __construct($data, $title = 'Ingresos')
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function headings(): array
    {
        return [
            'inventario_id',
            'descripcion',
            'serial',
            'fecha',
            'estado',
            'propietario',
            'bodega',
            'solicitante',
            'per_atiende',
            'transaccion_id',
            'justificacion',
            'cantidad',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 50,
            'C' => 12,
            'D' => 20,
            'E' => 12,
            'F' => 20,
            'G' => 15,
            'H' => 20,
            'I' => 22,
            'J' => 17,
            'K' => 40,
            'L' => 14,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if(is_numeric($value) && $value>9999){
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data;
    }

    public function styles(Worksheet $sheet)
    {
        $textoTitulo = [
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => 'ffffff',
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '0879dc', // Color gris claro
                ],
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

        $totalFilas = count($this->data) + self::TOTAL_FILAS_ENCABEZADO;

        // Aplica estilos al encabezado
        $sheet->getStyle('A1:L1')->applyFromArray($textoTitulo);

        // Aplica alineación y bordes al resto de la tabla
        $sheet->getStyle('A1:L' . $totalFilas)->applyFromArray($textCenter);
        $sheet->getStyle('A1:L' . $totalFilas)->applyFromArray($bordeTabla);

        // Envuelve texto y aplica negrita en la primera columna
        $sheet->getStyle('A1:L' . $totalFilas)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:A' . $totalFilas)->getFont()->setBold(true);

        // Activa el filtro automático
        $sheet->setAutoFilter('A1:L1');
    }
}
