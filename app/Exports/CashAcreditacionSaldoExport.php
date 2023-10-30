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

class CashAcreditacionSaldoExport extends DefaultValueBinder implements FromView, WithCustomValueBinder, ShouldAutoSize,WithStyles
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
        return view('exports.reportes.excel.cash_acreditacion_saldo', $this->reporte);
    }
    public function styles(Worksheet $sheet)
    {
        $columns = ['A', 'B', 'C', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'S', 'T'];
        foreach ($columns as $column) {
            $sheet->getStyle($column)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
    }
}
