<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSO\SeguimientoAccidenteRequest;
use App\Http\Resources\SSO\AccidenteResource;
use App\Http\Resources\SSO\SeguimientoAccidenteResource;
use App\Http\Resources\SSO\SeguimientoIncidenteResource;
use App\Http\Resources\Vehiculos\AsignacionVehiculoResource;
use App\Http\Resources\Vehiculos\VehiculoResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\SSO\Accidente;
use App\Models\SSO\SeguimientoAccidente;
use App\Models\Vehiculos\AsignacionVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\Permisos;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class SeguimientoAccidenteController extends Controller
{
    private string $entidad = 'Seguimiento de accidente';
    private ArchivoService $archivoService;

    public function __construct()
    {
        /*$this->middleware('can:puede.' . Permisos::VER . '.incidentes')->only('index', 'show');
        $this->middleware('can:puede.' . Permisos::CREAR . '.incidentes')->only('store');
        $this->middleware('can:puede.' . Permisos::EDITAR . '.incidentes')->only('update');*/
        $this->archivoService = new ArchivoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (request('export') == 'pdf') return $this->informeAccidente(request('seguimiento_accidente_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param SeguimientoAccidente $seguimiento_accidente
     * @return JsonResponse
     */
    public function show(SeguimientoAccidente $seguimiento_accidente)
    {
        $modelo = new SeguimientoAccidenteResource($seguimiento_accidente);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SeguimientoAccidenteRequest $request
     * @param SeguimientoAccidente $seguimiento_accidente
     * @return Response
     * @throws Throwable
     */
    public function update(SeguimientoAccidenteRequest $request, SeguimientoAccidente $seguimiento_accidente)
    {
        return DB::transaction(function () use ($request, $seguimiento_accidente) {
            $request->validated();

            $keys = $request->keys();
            unset($keys['id']);
            $seguimiento_accidente->update($request->only($keys));
            Log::channel('testing')->info('Log', ['Las keys son: ', $keys]);

            $modelo = new SeguimientoAccidenteResource($seguimiento_accidente->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    private function informeAccidente(int $seguimiento_accidente_id)
    {
        $seguimiento_accidente = SeguimientoAccidente::find($seguimiento_accidente_id);
        $configuracion = ConfiguracionGeneral::first();
        // $resource = new AsignacionVehiculoResource($asignacion);
        // $vehiculo = new VehiculoResource(Vehiculo::find($asignacion->vehiculo_id));
        $fecha_entrega = new DateTime($seguimiento_accidente->fecha_entrega);
//        try {

        $pdf = Pdf::loadView('sso.pdf.informe_accidente', [
            'configuracion' => $configuracion,
            'seguimiento_accidente' => (new SeguimientoAccidenteResource($seguimiento_accidente))->resolve(),
            'accidente' => (new AccidenteResource($seguimiento_accidente->accidente))->resolve(),
            // 'vehiculo' => $vehiculo->resolve(),
            'mes' => Utils::$meses[$fecha_entrega->format('F')],
            'entrega' => Empleado::find($seguimiento_accidente->entrega_id),
            'responsable' => Empleado::find($seguimiento_accidente->responsable_id),
        ]);
        $pdf->setPaper('A4');
        $pdf->render();
        return $pdf->output();
//        } catch (Throwable|Exception $th) {
//            throw ValidationException::withMessages(['error' => Utils::obtenerMensajeError($th, 'No se puede imprimir el pdf: ')]);
//        }
    }


    public function indexFiles(Request $request, SeguimientoAccidente $seguimiento_accidente)
    {
        try {
            $results = $this->archivoService->listarArchivos($seguimiento_accidente);
        } catch (Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     * @throws ValidationException|Throwable
     * @throws Throwable
     */
    public function storeFiles(Request $request, SeguimientoAccidente $seguimiento_accidente)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($seguimiento_accidente, $request['file'], RutasStorage::SEGUIMIENTOS_ACCIDENTES->value, 'SEGUIMIENTO ACCIDENTES');
            $mensaje = 'Archivo subido correctamente';
        } catch (\Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
