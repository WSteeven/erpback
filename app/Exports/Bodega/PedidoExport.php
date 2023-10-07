<?php

namespace App\Exports\Bodega;

use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class PedidoExport extends DefaultValueBinder implements FromView, WithCustomValueBinder, ShouldAutoSize, WithColumnWidths
{
    protected $pedidos;
    protected $configuracion;

    public function __construct($pedidos, $configuracion)
    {
        $this->pedidos = $pedidos;
        $this->configuracion = $configuracion;
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
        return view('pedidos.excel.pedidos', ['reporte' => $this->pedidos, 'configuracion' => $this->configuracion,]);
    }
}
