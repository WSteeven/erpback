<?php

namespace Src\App;

// use App\Http\Resources\SubtareaResource;
// use App\Models\ControlMaterialTrabajo;
use App\Models\ControlMaterialTrabajo;
// use App\Models\Empleado;
use App\Models\MaterialEmpleadoTarea;
/* use App\Models\Subtarea;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; */
use Illuminate\Validation\ValidationException;

class ControlMaterialTrabajoService
{
    private int $trabajo_id;
    /*public function __construct()
    {
    }*/

    public function setTrabajoId($trabajo_id)
    {
        $this->trabajo_id = $trabajo_id;
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

    /**
     * El material pasado para verificar debe estar previamente agregado
     * en la tabla material_empleado_tarea que son los materiales que el empleado
     * tiene a su disposicion para usar.
     */
    private function verificarEnStockStore($detalle_id, $cantidad)
    {
        $material = MaterialEmpleadoTarea::with('tarea')->where('detalle_producto_id', $detalle_id)->responsable()->first();

        if (!$material) throw ValidationException::withMessages([
            'marterial_insuficiente' => ['No se ha realizado el pedido del material solicitado.'],
        ]);

        if ($material->cantidad_stock < $cantidad) throw ValidationException::withMessages([
            'marterial_insuficiente' => ['No existe la cantidad suficiente del material para realizar esta transaccón.'],
        ]);
    }

    public function verificarDisponibleStockUpdate(array $materiales, array $materialesAnteriores)
    {
        foreach ($materiales as $material) {
            $this->verificarEnStockUpdate($material['detalle_producto_id'], $material['cantidad_utilizada'], $materialesAnteriores);
        }
    }

    private function verificarEnStockUpdate(int $detalle_id, int $cantidad, array $materialesAnteriores)
    {
        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)->first(); // stock
        if (!$material) throw ValidationException::withMessages([
            'marterial_insuficiente' => ['No se ha realizado el pedido del material solicitado.'],
        ]);

        if ($material->cantidad_stock < $cantidad) throw ValidationException::withMessages([
            'marterial_insuficiente' => ['No existe la cantidad suficiente del material para realizar esta transaccón.'],
        ]);
    }

    /**
     * Store
     * Se resta material del stock del empleado para tarea
     */
    public function computarMaterialesOcupados(array $materiales)
    {
        foreach ($materiales as $material) {
            $this->restarMaterial($material['detalle_producto_id'], $material['cantidad_utilizada']);
        }
    }

    /**
     * Update
     * Cuando se hace una actualizacion primero se suma el material ocupado anteriormente
     */
    public function computarMaterialesOcupadosUpdate(array $materiales)
    {
        foreach ($materiales as $material) {
            $this->sumaMaterialAnterior($material['detalle_producto_id']); //, $material['cantidad_utilizada']);
            $this->restarMaterial($material['detalle_producto_id'], $material['cantidad_utilizada']);
        }
    }

    // * Se resta material del stock del empleado para tarea
    public function restarMaterial(int $detalle_id, $cantidad)
    {
        $material = MaterialEmpleadoTarea::with('tarea')->where('detalle_producto_id', $detalle_id)->responsable()->first();
        $material->cantidad_stock -= $cantidad;
        $material->save();
    }

    public function sumaMaterialAnterior(int $detalle_id)
    {
        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)->responsable()->first();
        $materialAnterior = ControlMaterialTrabajo::with('trabajo')->where('detalle_producto_id', $detalle_id)->responsable()->first();
        //$materialAnterior = ControlMaterialTrabajo::where('detalle_producto_id', $detalle_id)->responsable()->first();

        $material->cantidad_stock += $materialAnterior->cantidad_utilizada;
        $material->save();
    }

    /* public function verificarPropietarioMaterial(array $materiales, $tarea) {
        $grupo = Auth::user()->empleado->grupo_id;

        $materialeAlmacenados = MaterialEmpleadoTarea::where('grupo_id', $grupo)->where('tarea_id', $tarea)->get();


    }*/
}
