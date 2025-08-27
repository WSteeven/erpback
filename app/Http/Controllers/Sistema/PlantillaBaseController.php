<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sistema\PlantillaBaseRequest;
use App\Http\Resources\Sistema\PlantillaBaseResource;
use App\Models\Sistema\PlantillaBase;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class PlantillaBaseController extends Controller
{
    public string $entidad = 'Plantilla';

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = PlantillaBase::filter()->get();
        PlantillaBaseResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PlantillaBaseRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(PlantillaBaseRequest $request)
    {
        $datos = $request->validated();

        if (!$request->hasFile('url')) throw new Exception('Debe seleccionar al menos un archivo.');
        $ruta_relativa = ArchivoService::guardarArchivoSingle($request->file('url'), RutasStorage::PLANTILLAS_BASE->value);

        $datos['url'] = $ruta_relativa;

        //Respuesta
        $modelo = PlantillaBase::create($datos);
        $modelo = new PlantillaBaseResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        // event(new PruebaEvent("Se ha creado una categoria nueva"));
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param PlantillaBase $plantilla
     * @return JsonResponse
     */
    public function show(PlantillaBase $plantilla)
    {
        $modelo = new PlantillaBaseResource($plantilla);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PlantillaBaseRequest $request
     * @param PlantillaBase $plantilla
     * @return JsonResponse
     * @throws Exception
     */
    public function update(PlantillaBaseRequest $request, PlantillaBase $plantilla)
    {
        $datos = $request->validated();

        if (!$request->hasFile('url')) throw new Exception('Debe seleccionar al menos un archivo.');
        $ruta_relativa = ArchivoService::guardarArchivoSingle($request->file('url'), RutasStorage::PLANTILLAS_BASE->value, $request->nombre, $plantilla->url);

        $datos['url'] = $ruta_relativa;

        //Respuesta
        $plantilla->update($datos);
        $modelo = new PlantillaBaseResource($plantilla->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PlantillaBase $plantilla
     * @return JsonResponse
     */
    public function destroy(PlantillaBase $plantilla)
    {
        $plantilla->delete();
        // no olvides de borrar el archivo del servidor
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    /**
     * @throws ValidationException
     */
    public function devolverArchivo(Request $request)
    {
        try {
            $request->validate(['nombre' => ['required', 'string']]);
            $plantilla = PlantillaBase::obtenerPlantillaByNombre($request['nombre']);

            $modelo = new PlantillaBaseResource($plantilla);
            return response()->json(compact('modelo'));
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }
}
