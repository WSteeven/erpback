<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\SolicitudExamenRequest;
use App\Http\Resources\Medico\SolicitudExamenResource;
use App\Models\Medico\SolicitudExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\App\Medico\SolicitudExamenService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class SolicitudExamenController extends Controller
{
    private $entidad = 'Solicitud de examen';
    private SolicitudExamenService $solicitudExamenService;
    private $archivoService;

    public function __construct(SolicitudExamenService $solicitudExamenService)
    {
        $this->middleware('can:puede.ver.solicitudes_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.solicitudes_examenes')->only('store');
        $this->middleware('can:puede.editar.solicitudes_examenes')->only('update');
        $this->middleware('can:puede.eliminar.solicitudes_examenes')->only('destroy');

        $this->solicitudExamenService = $solicitudExamenService;
        $this->archivoService = new ArchivoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::channel('testing')->info('Log', ['listado', 'Dentro de index solicitud examen']);
        $results = SolicitudExamen::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = SolicitudExamenResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(SolicitudExamenRequest $request)
    {
        $datosValidados = $request->validated();

        $modelo = $this->solicitudExamenService->crearSolicitudExamen($datosValidados);
        $modelo = new SolicitudExamenResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(SolicitudExamen $solicitud_examen)
    {
        $modelo = new SolicitudExamenResource($solicitud_examen);
        return response()->json(compact('modelo'));
    }

    public function update(SolicitudExamenRequest $request, $id)
    {
        $datosValidados = $request->validated();

        $modelo = $this->solicitudExamenService->actualizarSolicitudExamen($datosValidados, $id);
        $modelo = new SolicitudExamenResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, SolicitudExamen $solicitud_examen)
    {
        try {
            $results = $this->archivoService->listarArchivos($solicitud_examen);
        } catch (\Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, SolicitudExamen $solicitud_examen)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($solicitud_examen, $request->file, RutasStorage::SOLICITUD_EXAMEN->value . '_' . $solicitud_examen->id);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            return $ex;
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }
}
