<?php

namespace App\Exports\Ventas;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class CortePagoComisionExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithColumnWidths
{
    protected $reporte;
    protected $config;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
        $this->config = ConfiguracionGeneral::first();
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
        try {
            $reporte = $this->reporte;
            $config = $this->config;
            return view('ventas.excel.corte_pago_comision', compact('reporte', 'config'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
        ];
    }
}
