<?php

namespace App\Exports;

use App\Models\Inventario;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class KardexExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;
    function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Detalle',
            'N° transacción',
            'Motivo',
            'Tipo',
            'Stock anterior',
            'Cantidad',
            'Stock actual',
        ];
    }
    public function map($row): array
    {
        return [
            $row['fecha'],
            $row['detalle'],
            $row['num_transaccion'],
            $row['motivo'],
            $row['tipo'],
            $row['cant_anterior'],
            $row['cantidad'],
            $row['cant_actual'],
        ];
    }
    public function collection()
    {
        return $this->data;
    }
}
