<?php

namespace App\Exports\Bodega;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MaterialesVidaUtilResponsableExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    private mixed $datos;

    public function __construct(mixed $datos)
    {
        $this->datos = $datos;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return collect($this->datos);
    }

    public function headings(): array
    {
        return [
            'Responsable',
            'Producto',
            'Vida Útil (meses)',
            'Cantidad',
            'Fecha Despacho',
            'Fecha Vencimiento',
            'Vigencia',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'BDD7EE'],
                ]],//primera fila en negrita
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow(); // Obtener la ultima fila con datos

                //Aplica autofiltro solo a la columna A
                $sheet->setAutoFilter("A1:B$lastRow");
            }
        ];
    }
}
