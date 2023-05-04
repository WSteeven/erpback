<?php

namespace App\Exports;

use App\Http\Resources\InventarioResource;
use App\Http\Resources\InventarioResourceExcel;
use App\Models\Inventario;
use Doctrine\DBAL\Types\DateType;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InventarioExport extends DefaultValueBinder implements FromCollection,WithHeadings, WithStrictNullComparison, WithCustomValueBinder
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $sucursal_id;

    function __construct($sucursal_id){
        $this->sucursal_id = $sucursal_id;
    }

    public function headings():array{
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

    
    /* public function columnFormats(): array
    {
        return [
            'id' => NumberFormat::FORMAT_NUMBER,
            'producto'=>NumberFormat::FORMAT_TEXT,
            'descripcion'=>NumberFormat::FORMAT_TEXT,
            'categoria'=>NumberFormat::FORMAT_TEXT,
            'cliente'=>NumberFormat::FORMAT_TEXT,
            'serial'=>NumberFormat::FORMAT_TEXT,
            'sucursal'=>NumberFormat::FORMAT_TEXT,
            'condiciones'=>NumberFormat::FORMAT_TEXT,
            'por recibir'=>NumberFormat::FORMAT_NUMBER,
            'cantidad'=>NumberFormat::FORMAT_NUMBER,
            'por entregar'=>NumberFormat::FORMAT_NUMBER,
        ];
    } */
    
    public function bindValue(Cell $cell, $value)
    {
        if(is_numeric($value) && $value>9999){
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        return parent::bindValue($cell, $value);
    }
    public function collection()
    {
        $results = Inventario::where('sucursal_id', $this->sucursal_id)->get();
        return InventarioResourceExcel::collection($results);
    }
}
