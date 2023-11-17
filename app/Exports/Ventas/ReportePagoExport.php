<?php

namespace App\Exports\Ventas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ReportePagoExport extends DefaultValueBinder implements FromView, WithCustomValueBinder,ShouldAutoSize,WithColumnWidths
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }

    function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'E' || $cell->getColumn()=='L') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
    public function view(): View
    {
        return view('ventas.reporte_pago',$this->reporte);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 21,
            'C' => 5,
            'D' => 5,
            'E' => 13,
        ];
    }


}
