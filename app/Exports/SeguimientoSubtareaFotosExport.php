<?php

namespace App\Exports;

use App\Models\Emergencia;
use App\Models\Empleado;
use App\Models\MovilizacionSubtarea;
use App\Models\SeguimientoMaterialStock;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\SeguimientoSubtarea;
use App\Models\Subtarea;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// ---
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class SeguimientoSubtareaFotosExport implements FromView, WithBackgroundColor, WithTitle, WithColumnWidths
{
    use Exportable;

    protected Subtarea $subtarea;

    function __construct(Subtarea $subtarea)
    {
        $this->subtarea = $subtarea;
        $this->backgroundColor();
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 10,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 10,
            'N' => 10,
            'O' => 10,
            'P' => 10,
            'Q' => 10,
            'R' => 10,
            'S' => 10,
            'T' => 10,
            'U' => 10,
            'V' => 10,
            'W' => 10,
            'X' => 10,
            'Y' => 10,
            'Z' => 10,
        ];
    }

    public function title(): string
    {
        return 'EVIDENCIA';
    }

    public function view(): View
    {
        $subtarea = $this->subtarea;
        $fotos = $subtarea->actividadRealizadaSeguimientoSubtarea->map(fn ($actividad) => $actividad->fotografia);
        // Log::channel('testing')->info('Log', compact('materiales_tarea_usados'));

        return view('exports.reportes.excel.seguimiento_subtarea_fotos', compact('fotos'));
    }
}
