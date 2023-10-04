<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class CashRolPagoExport extends DefaultValueBinder implements FromView, WithCustomValueBinder
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }

    function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) && $value > 9999) {
            $val = str_replace(',', '', $value);
            $cell->setValueExplicit($val, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
    public function view(): View
    {
        return view('recursos-humanos.excel.cash_rol_pago', $this->reporte);
    }
}
