<?php

namespace App\Http\Controllers\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Resources\ControlPersonal\MarcacionResource;
use App\Models\ControlPersonal\Marcacion;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\ControlPersonal\AsistenciaService;
use Src\App\Sistema\PaginationService;
use Src\Shared\Utils;

class MarcacionController extends Controller
{
    public AsistenciaService $service;
    private PaginationService $paginationService;

    public function __construct()
    {
        $this->service = new AsistenciaService();
        $this->paginationService = new PaginationService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $paginate = $request->paginate;
        if($search) $query = Marcacion::whereHas('empleado', function ($query) use ($search) {
            $query->where('nombres', 'like', '%' . $search . '%')
                ->orWhere('apellidos', 'like', '%' . $search . '%');
        })->orWhere('fecha', 'like', '%' . $search . '%')->orderBy('fecha', 'desc');
        else $query = Marcacion::ignoreRequest(['campos', 'paginate'])->filter()->orderBy('fecha', 'desc');

        if ($paginate) $results = $this->paginationService->paginate($query, null, $request->page);
        else $results = $query->get();
        return MarcacionResource::collection($results);
//        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function store()
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
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
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function destroy()
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
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
