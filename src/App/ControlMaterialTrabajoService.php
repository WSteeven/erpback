<?php

namespace Src\App;

use App\Http\Resources\SubtareaResource;
use App\Models\ControlMaterialSubtarea;
use App\Models\ControlMaterialTrabajo;
use App\Models\Empleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Subtarea;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ControlMaterialTrabajoService
{
    public function __construct()
    {
    }

    /**
     * Recible arreglo de DetalleProducto
     * Devuelve una excepcion si la cantidad solicitada de un material supera el limite
     * de stock de la Tarea y Grupo asignado.
     */
    public function verificarDisponibleStock(array $materiales)
    {
        foreach ($materiales as $material) {
            $this->verificarEnStockStore($material['detalle_producto_id'], $material['cantidad_utilizada']);
        }
    }

    private function verificarEnStockStore($detalle_id, $cantidad)
    {
        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)->first();
        if (!$material) throw ValidationException::withMessages([
            'marterial_insuficiente' => ['No existe el material solicitado.'],
        ]);

        if ($material->cantidad_stock < $cantidad) throw ValidationException::withMessages([
            'marterial_insuficiente' => ['No existe la cantidad suficiente del material para realizar esta transaccón.'],
        ]);
    }

    private function verificarStockUpdate($detalle_id, $cantidad)
    {
        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)->first();
        if (!$material) throw ValidationException::withMessages([
            'marterial_insuficiente' => ['No existe el material solicitado.'],
        ]);

        if ($material->cantidad_stock < $cantidad) throw ValidationException::withMessages([
            'marterial_insuficiente' => ['No existe la cantidad suficiente del material para realizar esta transaccón.'],
        ]);
    }

    public function computarMaterialesOcupados(array $materiales)
    {
        foreach ($materiales as $material) {
            $this->restarMaterial($material['detalle_producto_id'], $material['cantidad_utilizada']);
        }
    }

    public function computarMaterialesOcupadosUpdate(array $materiales)
    {
        foreach ($materiales as $material) {
            $this->sumaMaterialAnterior($material['detalle_producto_id'], $material['cantidad_utilizada']);
            $this->restarMaterial($material['detalle_producto_id'], $material['cantidad_utilizada']);
        }
    }

    public function restarMaterial($detalle_id, $cantidad)
    {
        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)->first();
        $material->cantidad_stock -= $cantidad;
        $material->save();
    }

    public function sumaMaterialAnterior($detalle_id)
    {
        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)->first();
        $materialAnterior = ControlMaterialTrabajo::where('detalle_producto_id', $detalle_id)->first();

        $material->cantidad_stock += $materialAnterior->cantidad_utilizada;
        $material->save();
    }

    /* public function verificarPropietarioMaterial(array $materiales, $tarea) {
        $grupo = Auth::user()->empleado->grupo_id;

        $materialeAlmacenados = MaterialEmpleadoTarea::where('grupo_id', $grupo)->where('tarea_id', $tarea)->get();


    }*/
}
