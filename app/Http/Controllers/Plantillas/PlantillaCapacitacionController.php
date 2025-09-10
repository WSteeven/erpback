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
use Src\Shared\Utils;

class PlantillaCapacitacionController extends Controller
{
    private ReportePdfExcelService $reporteService;
    public string $entidad = 'Capacitación';

    /**
     * Listar capacitaciones.
     */
    public function index()
    {
        $results = PlantillaCapacitacion::with(['capacitador', 'asistentes'])
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return response()->json(compact('results'));
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

        $modelo = new PlantillaCapacitacionResource(
            $capacitacion->load(['capacitador', 'asistentes'])
        );
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Mostrar capacitación.
     */
    public function show(Request $request)
    {
        $id = $request->id; // viene como ?id=123
        $capacitacion = PlantillaCapacitacion::with(['capacitador', 'asistentes'])
            ->findOrFail($id);

        $modelo = new PlantillaCapacitacionResource($capacitacion);

        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar capacitación.
     */
    public function update(PlantillaCapacitacionRequest $request)
    {
        $id = $request->id;
        $data = $request->validated();

        $capacitacion = PlantillaCapacitacion::findOrFail($id);
        $capacitacion->update($data);

        if (isset($data['asistentes'])) {
            $capacitacion->asistentes()->sync($data['asistentes']);
        }

        $modelo = new PlantillaCapacitacionResource(
            $capacitacion->load(['capacitador', 'asistentes'])
        );
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar capacitación.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $capacitacion = PlantillaCapacitacion::findOrFail($id);
        $capacitacion->delete();

        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
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
