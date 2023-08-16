<?php

namespace App\Exports;

use App\Models\TransaccionBodega;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class TransaccionBodegaEgresoExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithStrictNullComparison, WithCustomValueBinder
{
    use Exportable;
    
    protected $data;
    public function __construct($data){
        $this->data = $data;
    }
    public function headings(): array
    {
        return [
            'inventario_id',
            'descripcion',
            'serial',
            'fecha',
            'estado',
            'propietario',
            'bodega',
            'responsable',
            'per_atiende',
            'transaccion_id',
            'justificacion',
            'cantidad',
        ];
    }
    
    public function bindValue(Cell $cell, $value)
    {
        if(is_numeric($value)&& $value>9999){
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        return parent::bindValue($cell, $value);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }
}
