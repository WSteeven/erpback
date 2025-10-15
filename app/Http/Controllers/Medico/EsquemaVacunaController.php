<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\EsquemaVacunaRequest;
use App\Http\Resources\Medico\EsquemaVacunaResource;
use App\Models\Medico\EsquemaVacuna;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class EsquemaVacunaController extends Controller
{
    private $entidad = 'Esquema de vacunacion';
    private $archivoService;

    public function __construct()
    {
        $this->middleware('can:puede.ver.esquemas_vacunas')->only('index', 'show');
        $this->middleware('can:puede.crear.esquemas_vacunas')->only('store');
        $this->middleware('can:puede.editar.esquemas_vacunas')->only('update');
        $this->middleware('can:puede.eliminar.esquemas_vacunas')->only('destroy');
        $this->archivoService = new ArchivoService();
    }

    public function index()
    {
        if (request('agrupar')) $results = EsquemaVacuna::ignoreRequest(['campos', 'agrupar'])->filter()->selectRaw('*, COUNT(*) as aplicadas')->groupBy('tipo_vacuna_id')->get();
        else $results = EsquemaVacuna::ignoreRequest(['campos'])->filter()->get();

//        Log::channel('testing')->info('Log', ['results', $results]);
        /* $results = $results->map(function ($esquema) {
            return [
                'tipo_vacuna' => $esquema->tipoVacuna?->nombre,
                'dosis_totales' => $esquema->tipoVacuna?->dosis_totales,
                'dosis_aplicadas' => $esquema->aplicadas,
                'tipo_vacuna_id' => $esquema->tipo_vacuna_id,
                'observacion' => $esquema->observacion,
                'fecha' => Carbon::parse($esquema->fecha)->format('Y-m-d'),
                'lote' => $esquema->lote,
                'responsable_vacunacion' => $esquema->responsable_vacunacion,
                'establecimiento_salud' => $esquema->establecimiento_salud,
                'es_dosis_unica' => $esquema->es_dosis_unica,
                'fecha_caducidad' => Carbon::parse($esquema->fecha_caducidad)->format('Y-m-d'),
            ];
        }); */
        $results = EsquemaVacunaResource::collection($results);

        // $results = EsquemaVacuna::where('pacie
        return response()->json(compact('results'));
    }

    public function store(EsquemaVacunaRequest $request)
    {
        try {
            DB::beginTransaction();

            $datos = $request->validated();
            $esquema_vacuna = EsquemaVacuna::create($datos);
            $modelo = [
                'id' => $esquema_vacuna->id,
                'tipo_vacuna' => $esquema_vacuna->tipoVacuna?->nombre,
                'dosis_totales' => $esquema_vacuna->tipoVacuna?->dosis_totales,
                'dosis_aplicadas' => EsquemaVacuna::where('paciente_id', $esquema_vacuna->paciente_id)->where('tipo_vacuna_id', $esquema_vacuna->tipo_vacuna_id)->count(),
                'tipo_vacuna_id' => $esquema_vacuna->tipo_vacuna_id,
                'fecha' => Carbon::parse($esquema_vacuna->fecha)->format('Y-m-d'),
                'lote' => $esquema_vacuna->lote,
                'responsable_vacunacion' => $esquema_vacuna->responsable_vacunacion,
                'establecimiento_salud' => $esquema_vacuna->establecimiento_salud,
                'observacion' => $esquema_vacuna->observacion,
            ];

            // $modelo = new EsquemaVacunaResource($esquema_vacuna);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(EsquemaVacunaRequest $request, EsquemaVacuna $esquema_vacuna)
    {
        $modelo = new EsquemaVacunaResource($esquema_vacuna);
        return response()->json(compact('modelo'));
    }


    public function update(EsquemaVacunaRequest $request, EsquemaVacuna $esquema_vacuna)
    {
        try {
            DB::beginTransaction();

            Log::channel('testing')->info('Log', ['esquema_vacuna', $esquema_vacuna]);

            $datos = $request->validated();
            Log::channel('testing')->info('Log', ['datos', $datos]);
            $esquema_vacuna->update($datos);
            $modelo = new EsquemaVacunaResource($esquema_vacuna->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(EsquemaVacunaRequest $request, EsquemaVacuna $esquema_vacuna)
    {
        try {
            DB::beginTransaction();
            $esquema_vacuna->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, EsquemaVacuna $esquema_vacuna)
    {
        try {
            $results = $this->archivoService->listarArchivos($esquema_vacuna);
        } catch (\Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, EsquemaVacuna $esquema_vacuna)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($esquema_vacuna, $request->file, RutasStorage::ESQUEMAS_VACUNAS->value . '_' . $esquema_vacuna->id);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            return $ex;
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }
}
