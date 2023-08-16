<?php

namespace App\Exports;

use App\Models\Emergencia;
use App\Models\SeguimientoSubtarea;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SeguimientoExport implements FromView
{
    use Exportable;

    protected SeguimientoSubtarea $seguimiento;

    function __construct(SeguimientoSubtarea $seguimiento)
    {
        $this->seguimiento = $seguimiento;
    }

    public function view(): View
    {

        return view('exports.reportes.excel.seguimiento_subtarea', $this->seguimiento);
    }
}
