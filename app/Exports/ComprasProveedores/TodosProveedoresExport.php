<?php

namespace App\Exports\ComprasProveedores;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class TodosProveedoresExport extends DefaultValueBinder implements   WithCustomValueBinder, FromView, ShouldAutoSize, WithColumnWidths
{
    protected $datos;
    protected $configuracion;

    public function __construct($datos)
    {
        $this->datos = $datos;
        $this->configuracion = ConfiguracionGeneral::first();
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
            'A' => 30,
            'B' => 30,
        ];
    }
    public function view(): View
    {
        return view('compras_proveedores.proveedores.excel.datos_completos', ['reporte' => $this->datos, 'configuracion' => $this->configuracion,]);
    }
}
