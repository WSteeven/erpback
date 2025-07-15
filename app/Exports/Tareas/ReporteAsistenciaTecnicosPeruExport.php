<?php

namespace App\Exports\Tareas;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReporteAsistenciaTecnicosPeruExport implements FromView, ShouldAutoSize,WithColumnWidths
{
    public ConfiguracionGeneral $configuracion;
    protected string $titulo;
    protected mixed $datos;

    public function __construct(array $datos, string $titulo)
    {
        $this->datos = $datos;
        $this->titulo = $titulo;
        $this->configuracion = ConfiguracionGeneral::first();
    }

    public function columnWidths(): array
    {
        return [
            'A'=>12,
            'B'=>30,
        ];
    }

    public function view(): View
    {
        return view('tareas.excel.reporte_asistencia_tecnicos_peru', ['registros' => $this->datos, 'configuracion' => $this->configuracion, 'titulo' => $this->titulo]);
    }
}
