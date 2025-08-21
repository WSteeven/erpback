<?php

namespace App\Exports\RecursosHumanos;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportePrestamosEmpresarialesExport implements FromView, ShouldAutoSize, WithTitle, WithColumnWidths
{
    protected ConfiguracionGeneral $configuracion;
    protected mixed $prestamos;
    public function __construct($prestamos, $configuracion)
    {
        $this->prestamos = $prestamos;
        $this->configuracion = $configuracion;
    }

    public function columnWidths(): array
    {
     return [
         'A' => 30,
         'B' => 30,
     ];
    }

    public function title(): string
    {
        return "Prestamos Empresariales {$this->configuracion->razon_social}";
    }

    public function view(): View
    {
        $prestamos = $this->prestamos;
        $configuracion = $this->configuracion;
     return view('recursos-humanos.nomina_permisos.excel.reporte_prestamos_empresariales', compact('prestamos', 'configuracion'));
    }

}
