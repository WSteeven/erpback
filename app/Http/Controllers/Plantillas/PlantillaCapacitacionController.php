<?php

namespace App\Http\Controllers\Plantillas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plantillas\PlantillaCapacitacionRequest;
use App\Http\Resources\Plantillas\PlantillaCapacitacionResource;
use App\Models\Plantillas\PlantillaCapacitacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\Plantillas\ReporteCapacitacionService;

class PlantillaCapacitacionController extends Controller
{
    private ReportePdfExcelService $reporteService;

    /**
     * Listar capacitaciones.
     */
    public function index()
    {
        $capacitaciones = PlantillaCapacitacion::with(['capacitador', 'asistentes'])
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return PlantillaCapacitacionResource::collection($capacitaciones);
    }

    /**
     * Guardar nueva capacitación.
     */
    public function store(PlantillaCapacitacionRequest $request)
    {
        $data = $request->validated();

        $capacitacion = PlantillaCapacitacion::create($data);

        if (!empty($data['asistentes'])) {
            $capacitacion->asistentes()->sync($data['asistentes']);
        }

        return new PlantillaCapacitacionResource(
            $capacitacion->load(['capacitador', 'asistentes'])
        );
    }

    /**
     * Mostrar capacitación.
     */
    public function show(Request $request)
    {
        $id = $request->id; // ahora viene como ?id=123
        $capacitacion = PlantillaCapacitacion::with(['capacitador', 'asistentes'])
            ->findOrFail($id);

        return new PlantillaCapacitacionResource($capacitacion);
    }

    /**
     * Actualizar capacitación.
     */
    public function update(PlantillaCapacitacionRequest $request)
    {
        $id = $request->id; // ahora viene como ?id=123
        $data = $request->validated();

        $capacitacion = PlantillaCapacitacion::findOrFail($id);
        $capacitacion->update($data);

        if (isset($data['asistentes'])) {
            $capacitacion->asistentes()->sync($data['asistentes']);
        }

        return new PlantillaCapacitacionResource(
            $capacitacion->load(['capacitador', 'asistentes'])
        );
    }

    /**
     * Eliminar capacitación.
     */
    public function destroy(Request $request)
    {
        $id = $request->id; // ahora viene como ?id=123
        $capacitacion = PlantillaCapacitacion::findOrFail($id);
        $capacitacion->delete();

        return response()->json(['message' => 'Capacitación eliminada correctamente']);
    }

    /**
     * Imprimir acta de capacitación en PDF.
     */
    public function imprimir(Request $request, $id)
    {
        $this->reporteService = new ReportePdfExcelService();

        $fecha_corte = $request->fecha_corte
            ? Carbon::parse($request->fecha_corte)
            : Carbon::now();

        $service = new ReporteCapacitacionService();

        $reporte = $service->generar([
            'capacitacion_id' => $id,
            'fecha_corte'     => $fecha_corte,
        ]);

        $nombre_reporte = 'acta_capacitacion_' . $id;
        $vista = 'plantillas.capacitacion_acta';

        return $this->reporteService->imprimirReporte(
            'pdf',
            'A4',
            'portrait',
            $reporte,
            $nombre_reporte,
            $vista
        );
    }
}
