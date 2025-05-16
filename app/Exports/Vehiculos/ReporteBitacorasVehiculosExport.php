<?php

namespace App\Exports\Vehiculos;

use App\Models\ConfiguracionGeneral;
use App\Models\Vehiculos\BitacoraVehicular;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReporteBitacorasVehiculosExport implements FromView, ShouldAutoSize
{
    protected Collection|BitacoraVehicular $results;
    public ConfiguracionGeneral $configuracion;
    public string $fecha_inicio;
    public string $fecha_fin;
    public int $umbral;
    public function __construct($data, $configuracion, $fecha_inicio, $fecha_fin, $umbral)
    {
        $this->results = $data;
        $this->configuracion = $configuracion;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->umbral = $umbral;
    }
    public function view(): View
    {
        return view('vehiculos.excel.bitacoras_vehiculos', [
            'reporte' => $this->results,
            'fecha_inicio' => $this->fecha_inicio,
            'umbral' => $this->umbral,
            'fecha_fin' => $this->fecha_fin,
            'configuracion' => $this->configuracion
        ]);
    }
}
