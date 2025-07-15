<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ExamenOrganoReproductivoRequest;
use App\Http\Resources\Medico\ExamenOrganoReproductivoResource;
use App\Models\Medico\ExamenOrganoReproductivo;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class ExamenOrganoReproductivoController extends Controller
{
    private string $entidad = 'Examen';
    public function __construct()
    {
//        $this->middleware('can:puede.ver.examenes_organos_reproductivos')->only('index', 'show');
        $this->middleware('can:puede.crear.examenes_organos_reproductivos')->only('store');
        $this->middleware('can:puede.editar.examenes_organos_reproductivos')->only('update');
        $this->middleware('can:puede.eliminar.examenes_organos_reproductivos')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = ExamenOrganoReproductivo::filter()->get();
        $results = ExamenOrganoReproductivoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExamenOrganoReproductivoRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(ExamenOrganoReproductivoRequest $request)
    {
        try {
            //Respuesta
            $modelo = ExamenOrganoReproductivo::create($request->validated());
            $modelo = new ExamenOrganoReproductivoResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable $th) {
            throw ValidationException::withMessages(['store' => '' . $th->getLine(), $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ExamenOrganoReproductivo $examen
     * @return JsonResponse
     */
    public function show(ExamenOrganoReproductivo $examen)
    {
        $modelo = new ExamenOrganoReproductivoResource($examen);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExamenOrganoReproductivoRequest $request
     * @param ExamenOrganoReproductivo $examen
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(ExamenOrganoReproductivoRequest $request, ExamenOrganoReproductivo $examen)
    {
        try {
            $examen->update($request->validated());
            $modelo = new ExamenOrganoReproductivoResource($examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable $th) {
            throw ValidationException::withMessages(['store' => '' . $th->getLine(), $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return void
     * @throws ValidationException
     */
    public function destroy()
    {
        try {
            throw new Exception('No se puede eliminar el examen', 422);
        } catch (Throwable $th) {
            throw ValidationException::withMessages(['store' => '' . $th->getLine(), $th->getMessage()]);
        }
    }
}
