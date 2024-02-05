<?php

namespace App\Exports\Ventas;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class CortePagoComisionExport extends DefaultValueBinder implements FromView, WithCustomValueBinder, ShouldAutoSize
{
    protected $reporte;

    function __construct($reporte){
        $this->reporte = $reporte;
    }

    function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) && $value > 9999) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function view(): View
    {
        return view('ventas.excel.corte_pago_comision', $this->reporte);
    }
}
