<?php

namespace App\Exports\Vehiculos;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdenReparacionExport implements FromCollection, WithHeadings,  WithStyles,WithColumnWidths
{
    private $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function headings(): array
    {
        return [
            'N° Orden',
            'Solicitante',
            'Autorizador',
            'Vehiculo',
            'Autorizacion',
            'Observacion',
            'Km realizado',
            'Fecha',
            'Servicios',
            'Valor Reparación',
            'Observación Aut.',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->results;
    }

    public function styles(Worksheet $sheet)
    {
//        return [
//            1 => ['font' => ['bold' => true]],
//        ];
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        // Habilitar ajuste de texto para todas las celdas
        $sheet->getStyle('F')->getAlignment()->setWrapText(true);
        $sheet->getStyle('I')->getAlignment()->setWrapText(true);
        $sheet->getStyle('K')->getAlignment()->setWrapText(true);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // fecha
            'B' => 35,  // solicitante
            'C' => 35,  // autorizador
            'D' => 12,  // vehículo
            'E' => 15,  // autorización
            'F' => 100,  // observación (puede ser más grande)
            'G' => 12,  // km_realizado
            'H' => 15,  // fecha
            'I' => 100,  // servicios (puede ser más grande)
            'J' => 15,  // valor_reparacion
            'K' => 100,  // motivo
        ];
    }
}
