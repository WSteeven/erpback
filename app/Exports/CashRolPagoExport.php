<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashRolPagoExport extends DefaultValueBinder implements FromView, WithCustomValueBinder,ShouldAutoSize,WithStyles
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
        return view('recursos-humanos.excel.cash_rol_pago', $this->reporte);
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
}
