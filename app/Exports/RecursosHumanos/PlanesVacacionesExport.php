<?php

namespace App\Exports\RecursosHumanos;

use App\Models\ConfiguracionGeneral;
use App\Models\RecursosHumanos\NominaPrestamos\PlanVacacion;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class PlanesVacacionesExport implements  FromView, WithTitle, ShouldAutoSize, WithColumnWidths
{
    protected  $planes_vacaciones;
    protected ConfiguracionGeneral $configuracion;
    public function __construct($planes_vacaciones, $configuracion)
    {
        $this->planes_vacaciones = $planes_vacaciones;
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
        $reporte= $this->planes_vacaciones;
        $configuracion= $this->configuracion;
        return view('recursos-humanos/nomina_permisos/excel/reporte_plan_vacaciones',
            compact('reporte', 'configuracion'));
//        [
//            'reporte'=> $this->planes_vacaciones,
//            'configuracion'=> $this->configuracion
//        ]);
    }
    public function title(): string
    {
     return 'Planes de Vacaciones';
    }
}
