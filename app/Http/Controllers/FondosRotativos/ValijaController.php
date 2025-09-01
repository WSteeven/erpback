<?php

namespace App\Http\Controllers\FondosRotativos;

use App\Exports\FondosRotativos\ValijaExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Gastos\GastoResource;
use App\Http\Resources\FondosRotativos\ValijaResource;
use App\Models\ConfiguracionGeneral;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Valija;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Exception;
use Src\Shared\Utils;

class ValijaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {

        $results = Gasto::whereHas('valijas')->get();
        $results = GastoResource::collection($results);
//        $results =  ValijaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function store()
    {
        throw ValidationException::withMessages(['error'=>Utils::metodoNoDesarrollado()]);
    }

    /**
     * Display the specified resource.
     *
     * @param Valija $valija
     * @return JsonResponse
     */
    public function show(Valija $valija)
    {
        $modelo = new ValijaResource($valija);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function update()
    {
        throw ValidationException::withMessages(['error'=>Utils::metodoNoDesarrollado()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function destroy()
    {
        throw ValidationException::withMessages(['error'=>Utils::metodoNoDesarrollado()]);
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws ValidationException
     * @throws \Exception
     */
    public function reporteValijas(Request $request, string $tipo){
        $configuracion = ConfiguracionGeneral::first();
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fecha_fin = Carbon::parse($request->fecha_fin)->endOfDay();
        $results = Valija::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();
        $results = ValijaResource::collection($results);
                    $peticion = $request->all();
        try {

        switch ($tipo) {
            case 'excel':
                return Excel::download(new ValijaExport($results, $configuracion, $peticion), 'reporte_valijas.xlsx');
                default: // pdf
                    $reporte = $results;//->resolve();
                    $pdf = Pdf::loadView('fondos_rotativos.valijas.reporte_valijas', compact(['reporte', 'peticion', 'configuracion']));
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->render();
                    return $pdf->stream();
        }
        }catch (Exception $e){
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }
}
