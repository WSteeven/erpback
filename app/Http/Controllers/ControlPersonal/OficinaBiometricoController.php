<?php

namespace App\Http\Controllers\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ControlPersonal\OficinaBiometricoRequest;
use App\Http\Resources\ControlPersonal\OficinaBiometricoResource;
use App\Models\ControlPersonal\OficinaBiometrico;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class OficinaBiometricoController extends Controller
{
    public string $entidad = 'Oficina';

    public function __construct()
    {
        $this->middleware('can:puede.ver.oficinas_biometricos')->only('index', 'show');
        $this->middleware('can:puede.crear.oficinas_biometricos')->only('store');
        $this->middleware('can:puede.editar.oficinas_biometricos')->only('update');
        $this->middleware('can:puede.eliminar.oficinas_biometricos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = OficinaBiometrico::filter()->orderBy('nombre')->get();
        $results = OficinaBiometricoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OficinaBiometricoRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(OficinaBiometricoRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $oficina = OficinaBiometrico::create($datos);
            $modelo = new OficinaBiometricoResource($oficina);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param OficinaBiometrico $oficina
     * @return JsonResponse
     */
    public function show(OficinaBiometrico $oficina)
    {
        $modelo = new OficinaBiometricoResource($oficina);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OficinaBiometricoRequest $request
     * @param OficinaBiometrico $oficina
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(OficinaBiometricoRequest $request, OficinaBiometrico $oficina)
    {
        try {
        DB::beginTransaction();
        $datos = $request->validated();

        $oficina->update($datos);
        $modelo = new OficinaBiometricoResource($oficina->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        DB::commit();
        }catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param OficinaBiometrico $oficina
     * @return Response
     * @throws ValidationException
     */
    public function destroy(OficinaBiometrico $oficina)
    {
        $mensaje = Utils::metodoNoDesarrollado();
        throw ValidationException::withMessages(['error' => "$mensaje, no se puede eliminar la oficina $oficina->nombre."]);
    }
}
