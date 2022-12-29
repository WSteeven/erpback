<?php

namespace Src\App;

use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\MaterialGrupoTarea;
use App\Models\Subtarea;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ControlMaterialSubtareaService
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
            $this->disponibleEnStock($material['detalle_producto_id'], $material['cantidad_utilizada']);
        }
    }

    private function disponibleEnStock($detalle_id, $cantidad)
    {
        $material = MaterialGrupoTarea::where('detalle_producto_id', $detalle_id)->first();
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

    public function restarMaterial($detalle_id, $cantidad)
    {
        $material = MaterialGrupoTarea::where('detalle_producto_id', $detalle_id)->first();
        $material->cantidad_stock -= $cantidad;
        $material->save();
    }

    /* public function verificarPropietarioMaterial(array $materiales, $tarea) {
        $grupo = Auth::user()->empleado->grupo_id;

        $materialeAlmacenados = MaterialGrupoTarea::where('grupo_id', $grupo)->where('tarea_id', $tarea)->get();


    }*/
}
