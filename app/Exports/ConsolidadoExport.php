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

class ConsolidadoExport extends DefaultValueBinder implements FromView, WithCustomValueBinder, ShouldAutoSize, WithColumnWidths
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
            'A'=>30,
            'B'=>33,
            'C'=>30,
            'D'=>23,
            'E'=>22,
            'F'=>27,
            'G'=>53,
            'H'=>42,
            'I'=>42,
            'J'=>42,
            'K'=>42,
            'L'=>42,
            'M'=>10,
            'N'=>10,
            'O'=>10,
        ];
    }
    public function view(): View
    {

        return view('exports.reportes.excel.reporte_consolidado.reporte_consolidado_usuario', $this->reporte);
    }
}
