<?php

namespace App\Exports\FondosRotativos;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ValijaExport implements FromView, ShouldAutoSize, WithColumnWidths
{
    use Exportable;

    public mixed $valijas;
    public mixed $peticion;
    public ConfiguracionGeneral $configuracion;
    public string $copyright;

    public function __construct($valijas, $configuracion, $peticion)
    {
        $this->valijas = $valijas;
        $this->peticion = $peticion;
        $this->configuracion = $configuracion;
        $this->copyright ='Esta informacion es propiedad de ' . $configuracion->razon_social . ' - Prohibida su divulgacion';
    }

    public function columnWidths(): array
    {
     return [
         'A'=>6,
         'B'=>20,
     ];
    }

    public function view(): View
    {
        return view('fondos_rotativos.valijas.excel.reporte_valijas', ['reporte' => $this->valijas, 'configuracion' => $this->configuracion, 'peticion' => $this->peticion, 'copyright' => $this->copyright]);
    }
}
