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
     */
    public function store(PlantillaBaseRequest $request)
    {
        Log::channel('testing')->info('Log', ['search-product',$request->hasFile('file'), request()->all()]);
        Log::channel('testing')->info('Log', ['request-file', request()->file('url'), request()->hasFile('url')]);

        //Respuesta
        $modelo = PlantillaBase::create($request->validated());
        $modelo = new PlantillaBaseResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        // event(new PruebaEvent("Se ha creado una categoria nueva"));
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function storeFile(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required|numeric',
                'file' => 'required|mimes:xls,xlsx'
            ]);
            if (!$request->hasFile('file')) {
                throw ValidationException::withMessages([
                    'file' => ['Debe seleccionar al menos un archivo.'],
                ]);
            }
            $plantilla = PlantillaBase::find($request->id);
            if (!$plantilla) throw new Exception('No se encontró una plantilla con ese ID para asociar el archivo.');

            $archivo = $request->file; // $request->file('file');
            $ruta = RutasStorage::PLANTILLAS_BASE->value;
            ArchivoService::crearDirectorioConPermisos($ruta);
            $path = $archivo->store($ruta);
            $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);

            $plantilla->url = $ruta_relativa;
            $plantilla->save();


            return response()->json(['mensaje' => 'Subido exitosamente!']);
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR al guardar el archivo de la plantilla base', $ex->getMessage(), $ex->getLine()]);
            throw ValidationException::withMessages([
                'file' => [$ex->getMessage(), $ex->getLine()],
            ]);
        }
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
     */
    public function update(PlantillaBaseRequest $request, PlantillaBase $plantilla)
    {
        //Respuesta
        $plantilla->update($request->validated());
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
}
