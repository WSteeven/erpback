<?php

namespace App\Http\Controllers\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Resources\ControlPersonal\MarcacionResource;
use App\Models\ControlPersonal\Marcacion;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\ControlPersonal\AsistenciaService;
use Src\Shared\Utils;

class MarcacionController extends Controller
{
    public AsistenciaService $service;

    public function __construct()
    {
        $this->service = new AsistenciaService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Marcacion::filter()->orderBy('fecha', 'desc')->get();

        $results = MarcacionResource::collection($results);
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
     * @param Marcacion $marcacion
     * @return JsonResponse
     */
    public function show(Marcacion $marcacion)
    {
        $modelo = new MarcacionResource($marcacion);
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
     * @throws ValidationException
     */
    public function sincronizarAsistencias()
    {
        try {
            $this->service->sincronizarAsistencias();
            return response()->json(['message' => 'Marcaciones sincronizadas correctamente.']);
        } catch (GuzzleException $e) {
            Log::channel('testing')->info('Log', ['GuzzleException en sincronizarAsistencias:', $e->getLine(), $e->getMessage()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'GuzzleException -> sincronizarAsistencias');
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e, 'sincronizarAsistencias');
        }
    }
}
