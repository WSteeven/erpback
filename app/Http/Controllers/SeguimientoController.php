<?php

namespace App\Http\Controllers;

use App\Exports\SeguimientoExport;
use App\Http\Requests\EmergenciaRequest;
use App\Http\Resources\EmergenciaResource;
use App\Models\Emergencia;
use App\Models\Seguimiento;
// use App\Models\Emergencia;
use App\Models\Subtarea;
use App\Models\TrabajoRealizado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Illuminate\Support\Facades\Storage;
use Src\App\SeguimientoService;

class SeguimientoController extends Controller
{
    private $entidad = 'Seguimiento';
    private $reporteService;
    private $seguimientoService;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->seguimientoService = new SeguimientoService();
    }

    public function index()
    {
        $results = Seguimiento::filter()->get();
        return response()->json(compact('results'));
    }

    public function show(Seguimiento $seguimiento)
    {
        $modelo = new EmergenciaResource($seguimiento);
        return response()->json(compact('modelo'));
    }

    public function store(EmergenciaRequest $request)
    {
        $datos = $request->validated();

        $modelo = Seguimiento::create($datos);

        // Guardar fotografias
        $this->seguimientoService->guardarFotografias($datos, $modelo);

        $subtarea = Subtarea::find($request->safe()->only(['subtarea'])['subtarea']);
        $subtarea->seguimiento_id = $modelo->id;
        $subtarea->save();

        $modelo = new EmergenciaResource($modelo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(EmergenciaRequest $request, Seguimiento $seguimiento)
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

        $modelo = new EmergenciaResource($seguimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    public function exportarSeguimiento(Seguimiento $seguimiento)
    {
        $tipo = 'excel';

        $export_excel = new SeguimientoExport($seguimiento);
        $vista = 'exports.reportes.excel.seguimiento_subtarea';
        $nombre_reporte = 'Juan Reporte';
        return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $seguimiento, $nombre_reporte, $vista, $export_excel);
    }
}
