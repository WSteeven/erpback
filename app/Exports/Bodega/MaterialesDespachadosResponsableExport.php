<?php

namespace App\Exports\Bodega;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Exception;

class MaterialesDespachadosResponsableExport implements FromView, WithCustomValueBinder, WithColumnWidths
{
    public array $data;
   protected ConfiguracionGeneral $configuracion;

   public function __construct($datos)
   {
       $this->data = $datos;

   }

    /**
     * @throws Exception
     */
    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value);

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
        return view('compras_proveedores.proveedores.excel.proveedores', [
            'reporte' => $this->proveedores,
            'configuracion' => $this->configuracion,
        ]);
    }


}
