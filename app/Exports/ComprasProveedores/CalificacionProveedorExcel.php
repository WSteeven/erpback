<?php

namespace App\Exports\ComprasProveedores;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class CalificacionProveedorExcel extends DefaultValueBinder implements FromView, WithCustomValueBinder
{
    protected $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
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

    public function view(): View
    {
        return view('compras_proveedores.proveedores.excel.calificacion_individual', ['reporte' => $this->datos]);
    }
}
