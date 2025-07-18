<?php

namespace App\Exports\Appenate\Telconet\Progresivas;

use App\Models\Appenate\Progresiva;
use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProgresivaExport implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithColumnWidths
{
    public ConfiguracionGeneral $configuracion;
    public Progresiva $progresiva;

    public function __construct(Progresiva $progresiva)
    {
        $this->progresiva = $progresiva;
        $this->configuracion = ConfiguracionGeneral::first();
    }

    public function view(): View
    {
        return view('appenate.telconet.excel.progresiva', ['progresiva' => $this->progresiva, 'configuracion' => $this->configuracion]);
    }

    public function title(): string
    {
        return 'Progresiva';
    }

    public function columnWidths(): array
    {
        return [
            // Columna F hasta T, puedes darle un mínimo decente
            'F' => 6,
            'G' => 6,
            'H' => 6,
            'I' => 6,
            'J' => 6,
            'K' => 6,
            'L' => 6,
            'M' => 6,
            'N' => 6,
            'O' => 6,
            'P' => 6,
            'Q' => 6,
            'R' => 6,
            'S' => 6,
            'T' => 6,
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Agrega bordes, negritas, alineacion, etc
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestDataRow();
                $highestCol = $sheet->getHighestDataColumn();

                // Construir el rango de las celdas a bordear
                $cellRange = 'A1:' . $highestCol . $highestRow;

                // Colocar todos los bordes al rango calculado
                $sheet->getStyle($cellRange)->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]]);

                $sheet->getStyle('A5:T9')->getAlignment()->setTextRotation(90);
                foreach (range(5, 9) as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(30);
                }


                $sheet->getDefaultRowDimension()->setRowHeight(20);
                $sheet->getPageSetup()->setFitToWidth(1);
            }
        ];
    }
}
