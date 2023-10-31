<?php

namespace App\Exports;

use App\Models\Emergencia;
use App\Models\SeguimientoSubtarea;
use App\Models\Subtarea;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SeguimientoExport implements FromView
{
    use Exportable;

    protected Subtarea $subtarea;

    function __construct(Subtarea $subtarea)
    {
        $this->subtarea = $subtarea;
    }

    public function view(): View
    {
        $subtarea = $this->subtarea;
        Log::channel('testing')->info('Log', compact('subtarea'));
        return view('exports.reportes.excel.seguimiento_subtarea', compact('subtarea'));
    }
}
