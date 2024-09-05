<?php

namespace App\Exports\Bodega;

use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class MaterialesDespachadosResponsableExport implements FromView, WithColumnWidths
{
    public Collection $data;
    public ConfiguracionGeneral $configuracion;
    public Empleado $bodeguero;
    public Empleado $responsable;

    public function __construct($datos, $bodeguero, $responsable)
    {
        $this->data = $datos;
        $this->bodeguero = $bodeguero;
        $this->responsable = $responsable;
        $this->configuracion = ConfiguracionGeneral::first();
    }


    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 30,
            'C' => 20,
            'D' => 40,
            'E' => 15,
            'F' => 15,
            'G' => 15,
        ];
    }

    public function view(): View
    {
        return view('bodega.excel.reporte_epps', [
            'reporte' => $this->data,
            'configuracion' => $this->configuracion,
            'bodeguero' => $this->bodeguero,
            'responsable' => $this->responsable
        ]);
    }


}
