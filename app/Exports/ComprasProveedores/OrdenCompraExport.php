<?php

namespace App\Exports\ComprasProveedores;

use App\Http\Resources\ComprasProveedores\OrdenCompraResource;
use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Throwable;

class OrdenCompraExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithColumnWidths, WithCustomValueBinder
{
    use Exportable;

    protected $ordenes;
    public $configuracion;

    public function __construct($data)
    {
        $this->ordenes = $data;
        $this->configuracion = ConfiguracionGeneral::first();
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
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

    /**
     * @throws Throwable
     */
    public function view(): View
    {
        //            Log::channel('testing')->info('Log', ['datos', $this->ordenes]);
        return view('compras_proveedores.ordenes_compras.excel.reporte_ordenes', ['reporte' => $this->ordenes, 'configuracion' => $this->configuracion]);
    }
}
