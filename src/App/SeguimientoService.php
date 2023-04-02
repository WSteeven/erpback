<?php

namespace Src\App;

// use App\Http\Resources\SubtareaResource;
use App\Http\Resources\TrabajoResource;
use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\Seguimiento;
use App\Models\Trabajo;
use App\Models\TrabajoRealizado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;

class SeguimientoService
{
    private MaterialesService $materialesService;

    public function __construct()
    {
        $this->materialesService = new MaterialesService();
    }

    public function guardarFotografias($datos, Seguimiento $modelo)
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

    public function descontarMaterialOcupado($datos, Seguimiento $modelo)
    {
        //
    }

    public function descontarMaterialStockOcupadoStore($request)
    {
        $materialesOcupados = $request['materiales_stock_ocupados'];

        foreach ($materialesOcupados as $materialOcupado) {
            $materialEmpleado = MaterialEmpleado::where('empleado_id', $request['empleado_id'])->where('detalle_producto_id', $materialOcupado['detalle_producto_id'])->first();
            $materialEmpleado->cantidad_stock = $materialEmpleado->cantidad_stock - $materialOcupado['cantidad_utilizada'];
            $materialEmpleado->save();
        }
    }

    public function descontarMaterialStockOcupadoUpdate($request)
    {
        $materialesOcupados = $request['materiales_stock_ocupados'];
        // Log::channel('testing')->info('Log', compact('materialesStock'));

        foreach ($materialesOcupados as $materialOcupado) {
            $materialEmpleado = MaterialEmpleado::where('empleado_id', $request['empleado_id'])->where('detalle_producto_id', $materialOcupado['detalle_producto_id'])->first();
            $materialEmpleado->cantidad_stock += (isset($materialOcupado['cantidad_old']) ? $materialOcupado['cantidad_old'] : 0)  - $materialOcupado['cantidad_utilizada'];
            $materialEmpleado->save();
        }
    }
}
