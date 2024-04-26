<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\DefaultValueBinder;

class GastoExport extends DefaultValueBinder implements FromView, WithCustomValueBinder, ShouldAutoSize, WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }
    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
    public function columnWidths(): array
    {
        return [
            'A'=>10,
            'B'=>33,
            'C'=>10,
            'D'=>10,
            'E'=>22,
            'F'=>22,
            'G'=>87,
            'H'=>60,
            'I'=>25,
            'J'=>25,
            'K'=>27,
            'L'=>10,
        ];
    }
    public function view(): View
    {
        return view('exports.reportes.excel.gastos_por_fecha_excel',$this->reporte);
    }
}
