<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Exception;

class GastoFiltradoExport extends DefaultValueBinder implements FromView,ShouldAutoSize,WithColumnWidths, WithCustomValueBinder
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }

    /**
     * @throws Exception
     */
    public function bindValue(Cell $cell, $value)
    {
        if(is_numeric($value)){
            $cell->setValueExplicit($value);

            return true;
        }
        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function view(): View
    {
//        Log::channel('testing')->info('Log', ['GastoFiltradoExport', $this->reporte]);
        return view('exports.reportes.excel.reporte_consolidado.reporte_gastos_filtrado',$this->reporte);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 37,
            'C' => 24,
            'D' => 10,
            'E' => 37,
            'F' => 78,
            'G' => 22,
            'H' => 250,
            'I' => 162,
            'J' => 37,
            'K' => 10,
            'L' => 10,
            'M' => 20,
            'N' => 10,
            'O' => 10,
        ];
    }
}
