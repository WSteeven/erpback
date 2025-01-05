<?php

namespace App\Exports;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class VacacionesPendientesExport implements FromView, WithTitle, ShouldAutoSize, WithColumnWidths
{
    protected $vacaciones;
    protected ConfiguracionGeneral $configuracion;

    public function __construct($vacaciones, $configuracion)
    {
        $this->vacaciones = $vacaciones;
        $this->configuracion = $configuracion;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
        ];
    }

    public function view(): View
    {
        $reporte = $this->vacaciones;
        $configuracion = $this->configuracion;
        return view('recursos-humanos/nomina_permisos/excel/reporte_vacaciones_pendientes', compact('reporte', 'configuracion'));
    }

    public function title(): string
    {
        return 'Vacaciones Pendientes';
    }

}
