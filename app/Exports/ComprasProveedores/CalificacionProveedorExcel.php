<?php

namespace App\Exports\ComprasProveedores;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;
use Throwable;

class CalificacionProveedorExcel extends DefaultValueBinder implements FromView, WithCustomValueBinder, ShouldAutoSize, WithColumnWidths
{
    protected $datos;
    public $configuracion;

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
        try {
            return view('compras_proveedores.proveedores.excel.calificacion_individual', ['reporte' => $this->datos, 'configuracion'=>$this->configuracion]);
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
