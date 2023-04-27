<?php

namespace App\Exports;

use App\Models\Emergencia;
use App\Models\Seguimiento;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SeguimientoExport implements FromView
{
    use Exportable;

    protected Seguimiento $seguimiento;

    function __construct(Seguimiento $seguimiento)
    {
        $this->seguimiento = $seguimiento;
    }

    public function view(): View
    {

        return view('exports.reportes.excel.seguimiento_subtarea', $this->seguimiento);
    }
}
