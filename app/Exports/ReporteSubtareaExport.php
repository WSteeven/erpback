<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class ReporteSubtareaExport implements WithMultipleSheets
{
    use Exportable;

    protected $subtarea;

    public function __construct($subtarea)
    {
        $this->subtarea = $subtarea;
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
