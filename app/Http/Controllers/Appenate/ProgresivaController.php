<?php

namespace App\Http\Controllers\Appenate;

use App\Exports\Appenate\Telconet\Progresivas\OrdenTrabajoExport;
use App\Exports\Appenate\Telconet\Progresivas\ProgresivaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appenate\ProgresivaRequest;
use App\Http\Resources\Appenate\ProgresivaResource;
use App\Http\Resources\CategoriaResource;
use App\Models\Appenate\Progresiva;
use DB;
use Excel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\Appenate\Telconet\ProgresivaService;
use Src\Shared\Utils;
use Throwable;

class ProgresivaController extends Controller
{
    private string $entidad = 'Progresiva';
    private ProgresivaService $service;


    public function __construct()
    {
        $this->service = new ProgresivaService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Progresiva::filter()->get();
        $results = ProgresivaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProgresivaRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(ProgresivaRequest $request)
    {
        Log::channel('testing')->info('Log', ['Progresivas::header:', $request->header('filename')]);
        Log::channel('testing')->info('Log', ['Progresivas::request:', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            Log::channel('testing')->info('Log', ['Progresivas::datos validados:', $datos]);
            $progresiva = Progresiva::create($datos);

            Log::channel('testing')->info('Log', ['Progresiva creada']);
            $this->service->actualizarRegistrosProgresivas($progresiva, $datos['registros_progresivas']);
            Log::channel('testing')->info('Log', ['Datos relacionados Progresiva actualizados']);

            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = $request->all();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['Error en el insert', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Progresiva $progresiva
     * @return JsonResponse
     */
    public function show(Progresiva $progresiva)
    {
        $modelo = new ProgresivaResource($progresiva);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProgresivaRequest $request
     * @param Progresiva $progresiva
     * @return JsonResponse
     */
    public function update(ProgresivaRequest $request, Progresiva $progresiva)
    {
        //Respuesta
        $progresiva->update($request->validated());
        $modelo = new CategoriaResource($progresiva->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Progresiva $progresiva
     * @return JsonResponse
     */
    public function destroy(Progresiva $progresiva)
    {
        $progresiva->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    /**
     * @throws ValidationException
     */
    public function leerRegistros()
    {
        try {
            $fastAPIProgresivasUrl = env('FAST_API_URL_DEFAULT') . 'progresivas';
            $response = Http::withOptions(['verify' => false])->get($fastAPIProgresivasUrl);
            Log::channel('testing')->info('Log', ['body:', $response->body()]);
            $mensaje = "Datos procesados con exito";
            $result = json_decode($response->body(), true);
            return response()->json(compact('mensaje', 'result'));
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['error:', $ex->getMessage(), $ex->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }


    /**
     * @throws ValidationException
     */
    public function imprimirOrdenTrabajo(Progresiva $progresiva)
    {
        try {
            return Excel::download(new OrdenTrabajoExport($progresiva), $progresiva->filename . '.xlsx');
        } catch (Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }

    /**
     * @throws ValidationException
     */
    public function imprimirMaterialesUtilizados(Progresiva $progresiva){
        try {
            return Excel::download(new ProgresivaExport($progresiva), $progresiva->filename . '_progresiva.xlsx');
        } catch (Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }

    /**
     * @throws ValidationException
     */
    public function imprimirKml(Progresiva $progresiva)
    {
        try {
            throw new Exception('Todavia no esta configurada');
        } catch (Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }
}
