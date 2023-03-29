<?php

namespace App\Exports;

use App\Models\Emergencia;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SeguimientoExport implements FromView
{
    use Exportable;

    protected Emergencia $emergencia;

    function __construct(Emergencia $emergencia)
    {
        $this->emergencia = $emergencia;
    }

    public function view(): View
    {

        return view('exports.reportes.excel.seguimiento_subtarea', $this->emergencia);
    }
}
