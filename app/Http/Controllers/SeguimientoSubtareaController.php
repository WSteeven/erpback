<?php

namespace App\Http\Controllers;

use App\Exports\SeguimientoExport;
use App\Http\Requests\SeguimientoSubtareaRequest;
use App\Http\Resources\SeguimientoSubtareaResource;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\SeguimientoSubtarea;
use App\Models\Subtarea;
use Illuminate\Support\Facades\DB;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\Shared\Utils;
use Illuminate\Support\Facades\Storage;
use Src\App\SeguimientoService;

class SeguimientoSubtareaController extends Controller
{
    private $entidad = 'SeguimientoSubtarea';
    private $reporteService;
    private $seguimientoService;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->seguimientoService = new SeguimientoService();
    }

    public function index()
    {
        $results = SeguimientoSubtarea::filter()->get();
        return response()->json(compact('results'));
    }

    public function show(SeguimientoSubtarea $seguimiento)
    {
        $modelo = new SeguimientoSubtareaResource($seguimiento);
        return response()->json(compact('modelo'));
    }

    public function store(SeguimientoSubtareaRequest $request)
    {
        $datos = $request->validated();

        $modelo = SeguimientoSubtarea::create($datos);

        $subtarea = Subtarea::find($request->safe()->only(['subtarea'])['subtarea']);
        $subtarea->seguimiento_id = $modelo->id;
        $subtarea->save();

        // Guardar fotografias
        $this->seguimientoService->guardarFotografias($datos, $modelo);
        // material de tarea
        $this->seguimientoService->descontarMaterialTareaOcupadoStore($request);
        $this->seguimientoService->registrarMaterialTareaOcupadoStore($request);
        // material de stock personal
        $this->seguimientoService->descontarMaterialStockOcupadoStore($request);

        $modelo = new SeguimientoSubtareaResource($modelo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(SeguimientoSubtareaRequest $request, SeguimientoSubtarea $seguimiento)
    {
        $datos = $request->validated();

        $seguimiento->update($datos);

        // Eliminar imagenes
        foreach ($seguimiento->trabajoRealizado as $trabajo) {
            $ruta = str_replace('storage', 'public', $trabajo->fotografia);
            Storage::delete($ruta);
        }

        // Eliminar fotografias anteriores
        $seguimiento->trabajoRealizado()->delete();

        // Guardar fotografias
        $this->seguimientoService->guardarFotografias($datos, $seguimiento);
        $this->seguimientoService->registrarMaterialTareaOcupadoUpdate($request);

        $this->seguimientoService->descontarMaterialTareaOcupadoUpdate($request);
        // fsdfsd
        $this->seguimientoService->descontarMaterialStockOcupadoUpdate($request);

        $modelo = new SeguimientoSubtareaResource($seguimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    public function exportarSeguimiento(SeguimientoSubtarea $seguimiento)
    {
        $tipo = 'excel';

        $export_excel = new SeguimientoExport($seguimiento);
        $vista = 'exports.reportes.excel.seguimiento_subtarea';
        $nombre_reporte = 'Juan Reporte';
        return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $seguimiento, $nombre_reporte, $vista, $export_excel);
    }

    // SELECT dp.descripcion, SUM(sms.cantidad_utilizada) AS total FROM seguimientos_materiales_subtareas sms inner join detalles_productos dp on sms.detalle_producto_id = dp.id group BY detalle_producto_id;
    public function obtenerSumaMaterialTareaUsado()
    {
        return DB::table('seguimientos_materiales_subtareas as sms')
        ->select('dp.descripcion', DB::raw('SUM(sms.cantidad_utilizada) AS total'))
        ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
        ->get();
    }
}
