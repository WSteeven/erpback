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
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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

             $sheet->getRowDimension(1)->setRowHeight(50);
             // Color de fondo en fila 2 (de A a H)
             $sheet->getStyle('A2:G2')->getFill()->setFillType(Fill::FILL_SOLID)
                 ->getStartColor()->setRGB('DCE6F1');
             $sheet->getStyle('A3:A11')->getFill()->setFillType(Fill::FILL_SOLID)
                 ->getStartColor()->setRGB('DCE6F1');
             $sheet->getStyle('A3:A11')->getFont()->setBold(true);


             $sheet->getStyle('D4:D5')->getFill()->setFillType(Fill::FILL_SOLID)
                 ->getStartColor()->setRGB('DCE6F1');
             $sheet->getStyle('D4:D5')->getFont()->setBold(true);
             $sheet->getStyle('D9:D10')->getFill()->setFillType(Fill::FILL_SOLID)
                 ->getStartColor()->setRGB('DCE6F1');
             $sheet->getStyle('D9:D10')->getFont()->setBold(true);

             // Color de fondo en fila 12 y 13 (de A a G)
             $sheet->getStyle('A12:G12')->getFill()->setFillType(Fill::FILL_SOLID)
                 ->getStartColor()->setRGB('BFBFBF');
             $sheet->getStyle('A13:G13')->getFill()->setFillType(Fill::FILL_SOLID)
                 ->getStartColor()->setRGB('DCE6F1');

             $sheet->getStyle('A14:G15')->getFill()->setFillType(Fill::FILL_SOLID)
                 ->getStartColor()->setRGB('BFBFBF');
             $sheet->getStyle('A14:G15')->getFont()->setBold(true);

             $sheet->getStyle('E17:G17')->getFill()->setFillType(Fill::FILL_SOLID)
                 ->getStartColor()->setRGB('DCE6F1');
             $sheet->getStyle('A17:G18')->getFont()->setBold(true);



             $sheet->getStyle('A1:G100')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

             $sheet->getStyle('A1:G3')->getFont()->setBold(true);
             $sheet->getStyle('A1:G3')->getAlignment()->setHorizontal('center');
             $sheet->getStyle('A12:G12')->getFont()->setBold(true);
             $sheet->getStyle('A12:G12')->getAlignment()->setHorizontal('center');
             $sheet->getStyle('A13:G13')->getFont()->setBold(true);
             $sheet->getStyle('A13:G13')->getAlignment()->setHorizontal('center');
             $sheet->getStyle('C8:G8')->getAlignment()->setHorizontal('justify');

             // Ajustes generales para que se vea mejor
             $sheet->getDefaultRowDimension()->setRowHeight(20);
             $sheet->getPageSetup()->setFitToWidth(1);
         }
     ];
    }


}
