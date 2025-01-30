<?php

namespace App\Exports\Vehiculos;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Src\Shared\Utils;
use Throwable;

class HistorialVehiculoExport implements FromView, ShouldAutoSize, WithColumnWidths
{
    protected $results;
    public $configuracion;
    public $request;


    public function __construct($data, $request)
    {
        $this->results = $data;
        $this->configuracion = ConfiguracionGeneral::first();
        $this->request = $request;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
        ];
    }

    /**
     * @throws Throwable
     */
    public function view(): View
    {
        try {
//            Log::channel('testing')->info('Log', ['Datos para la vista', $this->results]);
            return view('vehiculos.excel.historial_vehiculo', [
                'reporte' => $this->results,
                'request' => $this->request,
                'configuracion' => $this->configuracion
            ]);
        } catch (Throwable $th) {
            Log::channel('testing')->error('Log', ['Error en vista de excel', Utils::obtenerMensajeError($th)]);
            throw $th;
        }
    }
}
