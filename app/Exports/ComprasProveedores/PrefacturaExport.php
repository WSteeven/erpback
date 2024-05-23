<?php

namespace App\Exports\ComprasProveedores;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class PrefacturaExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithColumnWidths, WithCustomValueBinder
{
    use Exportable;

    protected $prefacturas;
    public $configuracion;

    public function __construct($data)
    {
        $this->prefacturas = $data;
        $this->configuracion = ConfiguracionGeneral::first();
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 30,
            'D' => 60,
            'G' => 100,
        ];
    }

    public function bindValue(Cell $cell, $value)
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
            return view('compras_proveedores.prefacturas.excel.reporte_prefacturas', ['reporte' => $this->prefacturas, 'configuracion' => $this->configuracion]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
