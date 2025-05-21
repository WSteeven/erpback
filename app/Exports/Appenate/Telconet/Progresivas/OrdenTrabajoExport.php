<?php

namespace App\Exports\Appenate\Telconet\Progresivas;

use App\Models\Appenate\Progresiva;
use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class OrdenTrabajoExport implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    public ConfiguracionGeneral $configuracion;
    public Progresiva $progresiva;

    public function __construct(Progresiva $progresiva)
    {
        $this->progresiva = $progresiva;
        $this->configuracion = ConfiguracionGeneral::first();
    }

    /**
     * @return View
     */
    public function view(): View
    {
//        $configuracion = $this->configuracion;
//        $progresiva = $this->progresiva;
//        return view('appenate.telconet.excel.orden_trabajo', compact('configuracion', 'progresiva')); //, ['progresiva' => $this->progresiva, 'configuracion' => $this->configuracion]);
        return view('appenate.telconet.excel.orden_trabajo', ['progresiva' => $this->progresiva, 'configuracion' => $this->configuracion]);
    }

    public function title(): string
    {
     return 'Ordenes de Trabajo';
    }

    public function registerEvents(): array
    {
     return [
         AfterSheet::class => function (AfterSheet $event) {
             // Agrega bordes, negritas, alineación, etc.
             $sheet = $event->sheet->getDelegate();

             $sheet->getRowDimension(1)->setRowHeight(40);

             $sheet->getStyle('A1:H100')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

             $sheet->getStyle('A1:H3')->getFont()->setBold(true);
             $sheet->getStyle('A1:H3')->getAlignment()->setHorizontal('center');

             // Ajustes generales para que se vea mejor
             $sheet->getDefaultRowDimension()->setRowHeight(20);
             $sheet->getPageSetup()->setFitToWidth(1);
         }
     ];
    }


}
