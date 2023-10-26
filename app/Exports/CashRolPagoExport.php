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

class CashRolPagoExport extends DefaultValueBinder implements FromView, WithCustomValueBinder,ShouldAutoSize
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
}
