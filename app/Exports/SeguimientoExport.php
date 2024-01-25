<?php

namespace App\Exports;

use App\Models\Emergencia;
use App\Models\Empleado;
use App\Models\MovilizacionSubtarea;
use App\Models\SeguimientoMaterialStock;
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
use Maatwebsite\Excel\Concerns\WithTitle;

class SeguimientoExport implements FromView, WithBackgroundColor, WithStyles, WithTitle
{
    use Exportable;

    protected Subtarea $subtarea;
    const BORDER = '1px solid #000';
    private $resumenAccionesRedesBackBone = [
        [
            'descripcion' => 'Asistencia cuadrilla (ruta otro contratista)',
            'detalle' => 'INSTALACION DE CABLE AEREA DE FO. 6H',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Tendido/Desmontaje de FO Soterrado',
            'detalle' => 'INSTALACION DE CABLE AEREA DE FO. 12H',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Tendido/Desmontaje/Recorrido de reserva de FO Aerea',
            'detalle' => 'INSTALACION DE CABLE AEREA DE FO. 24H',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Tendido de FO Insufladora',
            'detalle' => 'INSTALACION DE CABLE AEREA DE FO. 48H',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Armado/Intervención de Mangas Lineal',
            'detalle' => 'INSTALACION DE CABLE AEREA DE FO. 144H',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Armado/Intervención de Mangas Domo hasta 48h',
            'detalle' => 'INSTALACION DE CABLE TENSOR DE ACERO PARA CRUCE AMERICANO 1/4',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Armado/Intervención de Mangas Domo hasta 144h',
            'detalle' => 'INSTALACION DE MANGA DE EMPALME DE 144 H',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Armado/Intervención de Mini Manga',
            'detalle' => 'PRUEBA   UNIDIRECCIONAL DE TRANSMISIÓN FIBRA ÓPTICA (POR HILO. POR FIBRA. EN 2   VENTANAS) + TRAZA REFLECTOMÉTRICA THREAD /',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Fusion de Mangas Lineal',
            'detalle' => 'PRUEBA DE POTENCIA',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Fusion de Mangas Domo',
            'detalle' => 'INSTALACION DE ODF',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Fusion de Mini Manga',
            'detalle' => 'FUSIÓN DE 1 HILO DE FIBRA ÓPTICA',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Armado de ODF 96H',
            'detalle' => 'INSTALACION, MONTAJE, SELLADO Y ETIQUETADO DE CAJA CTO 8 Y 16 PUERTOS EXTERNA',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Fusion de ODF 96H',
            'detalle' => 'INSTALACION DE MANGA DE EMPALME DE 48',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Armado de ODF 48H',
            'detalle' => 'Suministro e Instalación Bajante en poste EMT 2" (5 mts) con accesorios',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Fusion de ODF 48H',
            'detalle' => 'SUMINISTRO E INSTALACIÓN TUBERÍA PVC 3"',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Armado de ODF 24H',
            'detalle' => 'ACONDICIONAMIENTO ZONA DURA (ESPESOR MENOR A 30 CM) PVC 3"',
            'border' => SeguimientoExport::BORDER,
            'background' => '#ededed',
        ],
        [
            'descripcion' => 'Fusion de ODF 24H',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Armado de ODF 12H',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Fusion de ODF 12H',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Armado de ODF 2H',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Fusion de ODF 2H',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Inst. Kit de bajante (Con materiales)',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Inst. Cruce Americano (Con materiales)',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Poda de arboles (m)',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Movilización (Km)',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Empaquetado básico de FO (m)',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Informe de Inspeccion',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
        [
            'descripcion' => 'Asistencia a Nodo',
            'detalle' => '',
            'border' => 'none',
            'background' => '#fff',
        ],
    ];

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

        for ($columna = 1; $columna <= $cantidad_filas; $columna++) {
            $sheet->getStyle('F' . $columna)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        }
    }

    public function title(): string
    {
        return 'GENERAL'; // Nombre de la primera hoja
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
        $materiales_stock_usados = $this->obtenerMaterialesStockUsados();
        $resumenAccionesRedesBackBone = $this->resumenAccionesRedesBackBone;
        // Log::channel('testing')->info('Log', compact('materiales_tarea_usados'));

        return view('exports.reportes.excel.seguimiento_subtarea', compact('subtarea', 'fecha_actual', 'reporte_generado_por', 'empleados_designados', 'fecha_hora_arribo_personal', 'fecha_hora_retiro_personal', 'materiales_tarea_usados', 'materiales_stock_usados', 'resumenAccionesRedesBackBone'));
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
        return $materialTareaUsado;
    }

    private function obtenerMaterialesStockUsados()
    {
        $empleado_id = $this->subtarea->empleado_id;
        $materialStockUsado = SeguimientoMaterialStock::selectRaw('detalles_productos.descripcion, SUM(cantidad_utilizada) as cantidad_utilizada')
            ->where('empleado_id', $empleado_id)
            ->where('subtarea_id', $this->subtarea->id)
            ->groupBy('detalle_producto_id')
            ->join('detalles_productos', 'seguimientos_materiales_stock.detalle_producto_id', '=', 'detalles_productos.id')
            ->get();
        return $materialStockUsado;
    }
}
