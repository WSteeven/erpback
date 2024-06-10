<?php

namespace App\Exports\RecursosHumanos\Alimentacion;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashAlimentacionExport extends DefaultValueBinder implements FromView, WithCustomValueBinder,ShouldAutoSize,WithStyles,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }

    function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'G') {
            $val = str_replace(',', '', $value);
            $numeroFormateado = str_pad($val, 13, '0', STR_PAD_LEFT);
            $cell->setValueExplicit($numeroFormateado, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
    public function view(): View
    {
        return view('recursos-humanos.excel.cash_alimentacion', $this->reporte);
    }
    public function styles(Worksheet $sheet)
    {
        $columns_izquierda = ['A','F','H','J','L','N','S','T'];
        $columns_derecha = ['B','C','E','G','I','K','M'];
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
