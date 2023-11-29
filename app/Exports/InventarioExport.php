<?php

namespace App\Exports;

use App\Http\Resources\InventarioResourceExcel;
use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class InventarioExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithStrictNullComparison, WithCustomValueBinder
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $sucursal_id;

    function __construct($sucursal_id)
    {
        $this->sucursal_id = $sucursal_id;
    }

    public function headings(): array
    {
        return [
            'id',
            'producto',
            'descripcion',
            'categoria',
            'cliente',
            'serial',
            'sucursal',
            'condiciones',
            'por recibir',
            'cantidad',
            'por entregar',
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
    public function collection()
    {
        if ($this->sucursal_id == 0) {
            $results = Inventario::where('cantidad', '>', 0)->get();
        } else {
            $results = Inventario::where('sucursal_id', $this->sucursal_id)
                ->where('cantidad', '>', 0)->get();
        }
        return InventarioResourceExcel::collection($results);
    }
}
