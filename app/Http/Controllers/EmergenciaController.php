<?php

namespace App\Http\Controllers;

use App\Exports\SeguimientoExport;
use App\Http\Requests\EmergenciaRequest;
use App\Http\Resources\EmergenciaResource;
use App\Models\Emergencia;
// use App\Models\Emergencia;
use App\Models\Subtarea;
use Illuminate\Http\Request;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\Shared\Utils;

class EmergenciaController extends Controller
{
    private $entidad = 'Registro';
    private $reporteService;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
    }

    public function index()
    {
        $results = Emergencia::filter()->get();
        return response()->json(compact('results'));
    }

    public function show(Emergencia $emergencia)
    {
        $modelo = new EmergenciaResource($emergencia);
        return response()->json(compact('modelo'));
    }

    public function store(EmergenciaRequest $request)
    {
        $datos = $request->validated();
        //$modelo = new EmergenciaResource($modelo);
        $modelo = Emergencia::create($datos);

        $subtarea = Subtarea::find($request->safe()->only(['subtarea'])['subtarea']);
        $subtarea->emergencia_id = $modelo->id;
        $subtarea->save();

        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(EmergenciaRequest $request, Emergencia $emergencia)
    {
        $datos = $request->validated();

        $emergencia->update($datos);
        $modelo = new EmergenciaResource($emergencia->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    public function exportarSeguimiento(Emergencia $emergencia)
    {
        $tipo = 'excel';

        $export_excel = new SeguimientoExport($emergencia);
        $vista = 'exports.reportes.excel.seguimiento_subtarea';
        $nombre_reporte = 'Juan Reporte';
        return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $emergencia, $nombre_reporte, $vista, $export_excel);
    }
}
