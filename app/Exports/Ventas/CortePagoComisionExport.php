<?php

namespace App\Exports\Ventas;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class CortePagoComisionExport implements WithMultipleSheets
{
    protected $reporte;
    protected $ventas;
    protected $config;

    function __construct($reporte, $ventas)
    {
        $this->reporte = $reporte;
        $this->ventas = $ventas;
        $this->config = ConfiguracionGeneral::first();
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[1] = new CortePagosComisionExport($this->reporte, $this->config);
        $sheets[2] = new VentasCorteComisionExport($this->ventas, $this->config);

        return $sheets;
    }
}
class CortePagosComisionExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithColumnWidths
{
    protected $reporte;
    protected $config;

    public function __construct($proveedores, $configuracion)
    {
        $this->reporte = $proveedores;
        $this->config = $configuracion;
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

class VentasCorteComisionExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithColumnWidths
{
    protected $reporte;
    protected $config;

    public function __construct($ventas, $configuracion)
    {
        $this->reporte = $ventas;
        $this->config = $configuracion;
    }
    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) && $value > 9999) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
    public function view(): View
    {
        try {
            $reporte = $this->reporte;
            $config = $this->config;
            return view('ventas.excel.corte_ventas_comision', compact('reporte', 'config'));
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
