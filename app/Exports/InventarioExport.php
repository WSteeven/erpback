<?php

namespace App\Exports;

use App\Http\Resources\InventarioResource;
use App\Http\Resources\InventarioResourceExcel;
use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InventarioExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
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
    public function collection()
    {
        $results = Inventario::where('sucursal_id', $this->sucursal_id)->get();
        return InventarioResourceExcel::collection($results);
    }
}
