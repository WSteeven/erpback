<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ReporteSubtareaExport implements WithMultipleSheets //, WithBackgroundColor
{
    use Exportable;

    protected $subtarea;

    public function __construct($subtarea)
    {
        $this->subtarea = $subtarea;
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    // Agregar pestañas
    public function sheets(): array
    {
        $sheets = [];
        $sheets[1] = new SeguimientoExport($this->subtarea);
        $sheets[2] = new SeguimientoSubtareaFotosExport($this->subtarea);

        return $sheets;
    }
}
