<?php

namespace App\Exports;

use App\Models\Emergencia;
use App\Models\Empleado;
use App\Models\MovilizacionSubtarea;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\SeguimientoSubtarea;
use App\Models\Subtarea;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
// ---
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SeguimientoExport implements FromView, WithBackgroundColor, WithStyles
{
    use Exportable;

    protected Subtarea $subtarea;

    function __construct(Subtarea $subtarea)
    {
        $this->subtarea = $subtarea;
        $this->backgroundColor();
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    public function styles(Worksheet $sheet)
    {
        $cantidad_filas = 100;

        for($columna = 1 ; $columna <= $cantidad_filas ; $columna++) {
            $sheet->getStyle('F' . $columna)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        }

    }

    public function view(): View
    {
        $subtarea = $this->subtarea;
        $fecha_actual = $this->obtenerFechaActual();
        $reporte_generado_por = Empleado::extraerNombresApellidos(Auth::user()->empleado);
        $empleados_designados = $this->obtenerEmpleadosDesignados();
        $fecha_hora_arribo_personal = $this->obtenerFechaHoraArriboPersonal();
        $fecha_hora_retiro_personal = $this->obtenerFechaHoraRetiroPersonal();
        $materiales_tarea_usados = $this->obtenerMaterialesTareaUsados();
        // Log::channel('testing')->info('Log', compact('materiales_tarea_usados'));

        return view('exports.reportes.excel.seguimiento_subtarea', compact('subtarea', 'fecha_actual', 'reporte_generado_por', 'empleados_designados', 'fecha_hora_arribo_personal', 'fecha_hora_retiro_personal', 'materiales_tarea_usados'));
    }

    private function obtenerFechaActual()
    {
        $fechaActual = Carbon::now();
        return $fechaActual->isoFormat('dddd, D [de] MMMM [del] YYYY', 'Do [de] MMMM [del] YYYY');
    }

    private function obtenerEmpleadosDesignados()
    {
        $empleados_designados = [];

        foreach ($this->subtarea->empleados_designados as $empleado_id) {
            array_push($empleados_designados, Empleado::extraerNombresApellidos(Empleado::find($empleado_id)));
        }

        return $empleados_designados;
    }

    private function obtenerFechaHoraArriboPersonal()
    {
        $empleado_id = $this->subtarea->empleado_id;
        $movilizacion = MovilizacionSubtarea::where('subtarea_id', $this->subtarea->id)->where('empleado_id', $empleado_id)->where('motivo', 'IDA')->first();
        return $movilizacion ? $movilizacion->fecha_hora_llegada : '';
    }

    private function obtenerFechaHoraRetiroPersonal()
    {
        $empleado_id = $this->subtarea->empleado_id;
        $movilizacion = MovilizacionSubtarea::where('subtarea_id', $this->subtarea->id)->where('empleado_id', $empleado_id)->where('motivo', 'REGRESO')->first();
        return $movilizacion ? $movilizacion->fecha_hora_salida : '';
    }

    private function obtenerMaterialesTareaUsados()
    {
        $empleado_id = $this->subtarea->empleado_id;
        $materialTareaUsado = SeguimientoMaterialSubtarea::selectRaw('detalles_productos.descripcion, SUM(cantidad_utilizada) as cantidad_utilizada')
            ->where('empleado_id', $empleado_id)
            ->where('subtarea_id', $this->subtarea->id)
            ->groupBy('detalle_producto_id')
            ->join('detalles_productos', 'seguimientos_materiales_subtareas.detalle_producto_id', '=', 'detalles_productos.id')
            ->get();
        // ->join('empleados', 'tickets.responsable_id', '=', 'empleados.id')
        return $materialTareaUsado;
        // return $materialTareaUsado->map(fn($materialTarea) => [

        // ]);
    }
}
