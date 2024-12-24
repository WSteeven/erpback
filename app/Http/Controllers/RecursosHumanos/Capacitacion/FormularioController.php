<?php

namespace App\Http\Controllers\RecursosHumanos\Capacitacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\Capacitacion\FormularioRequest;
use App\Http\Resources\RecursosHumanos\Capacitacion\FormularioResource;
use App\Models\RecursosHumanos\Capacitacion\Formulario;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Log;
use Src\Shared\Utils;

class FormularioController extends Controller
{
    private string $entidad = 'Formulario';

    public function __construct()
    {
        $this->middleware('can:puede.crear.rrhh_capacitacion_formularios')->only('store');
        $this->middleware('can:puede.editar.rrhh_capacitacion_formularios')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_capacitacion_formularios')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Formulario::all();
        $results = FormularioResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FormularioRequest $request
     * @return JsonResponse
     */
    public function store(FormularioRequest $request)
    {
        Log::channel('testing')->info('Log', ['FormularioController::store -> request', $request->all()]);
        $modelo = Formulario::create($request->validated());
        $modelo = new FormularioResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        // event(new PruebaEvent("Se ha creado una categoria nueva"));
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Formulario $formulario
     * @return JsonResponse
     * @throws ValidationException
     */
    public function show(Formulario $formulario)
    {
        try {

            if ($formulario->tipo === Formulario::INTERNO)
                if (!auth('sanctum')->check())
                    throw new Exception('Este formulario solo está disponible para usuarios autenticados');
            $modelo = new FormularioResource($formulario);
            return response()->json(compact('modelo'));
        } catch (Exception $e) {
            throw  Utils::obtenerMensajeErrorLanzable($e);
//            return response()->json('Error: '.$e->getMessage(), 500);
//            throw ValidationException::withMessages(['error1' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FormularioRequest $request
     * @param Formulario $formulario
     * @return JsonResponse
     */
    public function update(FormularioRequest $request, Formulario $formulario)
    {
        //Respuesta
        $formulario->update($request->validated());
        $modelo = new FormularioResource($formulario->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Formulario $formulario
     * @return JsonResponse
     */
    public function destroy(Formulario $formulario)
    {
        $formulario->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
