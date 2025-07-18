<?php

namespace App\Exports\Bodega;

use App\Exports\TransaccionBodegaIngresoExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MaterialesStockExport implements WithMultipleSheets
{
    use Exportable;

    public array $datos;

    /**
     * Constructor.
     */
    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Agregar hoja de índice
        $sheets[0] = new IndexSheetExport();

        // Agregar las demás hojas
        $sheets[1] = new MaterialesStockTodosEgresosExport($this->datos['egresos'], 'Egresos');
        $sheets[2] = new SumaProductosStockExport($this->datos['egresos_suma'], 'Suma egresos');
        $sheets[3] = new TransaccionBodegaIngresoExport($this->datos['devoluciones'], 'Devoluciones');
        $sheets[4] = new SumaProductosStockExport($this->datos['devoluciones_suma'], 'Suma devoluciones');
        $sheets[5] = new TransferenciasProductosEmpleadosExport($this->datos['transferencias_recibidas'], 'Transferencias recibidas');
        $sheets[6] = new SumaProductosStockExport($this->datos['transferencias_recibidas_suma'], 'Suma transferencias recibidas');
        $sheets[7] = new TransferenciasProductosEmpleadosExport($this->datos['transferencias_enviadas'], 'Transferencias enviadas');
        $sheets[8] = new SumaProductosStockExport($this->datos['transferencias_enviadas_suma'], 'Suma transferencias enviadas');
        $sheets[9] = new PreingresosExport($this->datos['preingresos'], 'Preingresos');
        $sheets[10] = new SumaProductosStockExport($this->datos['preingresos_suma'], 'Suma preingresos');
        $sheets[11] = new SeguimientoMaterialStockExport($this->datos['ocupado_en_tareas'], 'Material de stock usado');
        $sheets[12] = new SumaProductosStockExport($this->datos['seguimientos_materiales_stock_suma'], 'Suma material de stock usado');
        $sheets[13] = new SeguimientoMaterialStockExport($this->datos['material_tarea_ocupado_en_tareas'], 'Material de tarea usado');
        $sheets[14] = new SumaProductosStockExport($this->datos['material_tarea_ocupado_en_tareas_suma'], 'Suma de material de tarea usado');
        $sheets[15] = new SumaProductosStockExport($this->datos['stock_actual'], 'Stock actual');

        return $sheets;
    }
}
