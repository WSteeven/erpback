<?php

namespace Src\App;

// use App\Http\Resources\SubtareaResource;
use App\Http\Resources\TrabajoResource;
use App\Models\Empleado;
use App\Models\Seguimiento;
use App\Models\Trabajo;
use App\Models\TrabajoRealizado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;

class SeguimientoService
{
    public function guardarFotografias($datos, $modelo)
    {
        foreach ($datos['trabajo_realizado'] as $trabajo) {
            $trabajoRealizado = new TrabajoRealizado();
            $trabajoRealizado->fotografia = (new GuardarImagenIndividual($trabajo['fotografia'], RutasStorage::SEGUIMIENTO))->execute();
            $trabajoRealizado->fecha_hora = Carbon::parse($trabajo['fecha_hora'])->format('Y-m-d H:i:s');
            $trabajoRealizado->trabajo_realizado = $trabajo['trabajo_realizado'];
            $trabajoRealizado->seguimiento_id = $modelo->id;
            $trabajoRealizado->save();
        }
    }

    public function descontarMaterialOcupado()
    {
        //
    }

    public function descontarMaterialStockOcupado()
    {
        //
    }
}
