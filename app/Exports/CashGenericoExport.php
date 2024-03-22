<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashGenericoExport extends DefaultValueBinder implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles, WithStrictNullComparison, WithCustomValueBinder
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function headings(): array
    {
        // NUMERO DE CUENTA DE EMPRESA	NUMERO SECUENCIAL 	NUMERO DE COMPROBANTE DE PAGO	CODIGO DE BENEFICARIO Y/O EMPLEADO	MONEDA	VALOR	FORMA DE PAGO	CODIGO DE BANCO	TIPO DE CUENTA	NUMERO DE CUENTA	TIPO DE DOCUMENTO DE BENEFICARIO Y/O EMPLEADO	NUMERO DE CEDULA DE  BENEFICARIO Y/O EMPLEADO	NOMBRES DE  BENEFICARIO Y/O EMPLEADO	DIRECCION  BENEFICARIO Y/O EMPLEADO	CIUDAD BENEFICARIO Y/O EMPLEADO	TELEFONO BENEFICARIO Y/O EMPLEADO	LOCALIDAD DE COBRO	REFERENCIA	REFERENCIA ADICIONAL

        return [
            'TIPO',
            'N. CUENTA',
            'N. SECUENCIAL',
            'N. COMPROBANTE',
            'COD. BENEFICIARIO',
            'MONEDA',
            'VALOR',
            'FORMA DE PAGO',
            'CODIGO DE BANCO',
            'TIPO DE CUENTA',
            'NUMERO DE CUENTA',
            'TIPO DOCUMENTO',
            'CEDULA BENEFICIARIO',
            'NOMBRES BENEFICARIO',
            'DIRECCION',
            'CIUDAD',
            'TELEFONO',
            'LOCALIDAD',
            'REFERENCIA',
            'REFERENCIA ADICIONAL',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'G') {
            $val = str_replace(',', '', $value);
            $numeroFormateado = str_pad($val, 13, '0', STR_PAD_LEFT);
            $cell->setValueExplicit($numeroFormateado, DataType::TYPE_STRING);
            return true;
        }
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        return parent::bindValue($cell, $value);
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
        $columns_izquierda = ['A', 'F', 'H', 'J', 'L', 'N', 'S', 'T'];
        $columns_derecha = ['B', 'C', 'E', 'G', 'I', 'K', 'M'];
        foreach ($columns_izquierda as $column_izquierda) {
            $sheet->getStyle($column_izquierda)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
        foreach ($columns_derecha as $column_derecha) {
            $sheet->getStyle($column_derecha)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 13.56,
            'B' => 15.67,
            'C' => 14.56,
            'E' => 14.11,
            'F' => 16.11,
            'G' => 15.11,
            'H' => 15.67,
            'I' => 17.78,
            'J' => 13.11,
            'K' => 14.11,
            'L' => 15.11,
            'M' => 16.11,
            'N' => 19.67,
            'S' => 29.11,
            'T' => 10.78
        ];
    }
}
