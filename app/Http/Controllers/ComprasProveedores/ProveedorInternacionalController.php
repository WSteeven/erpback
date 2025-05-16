<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\ProveedorInternacionalRequest;
use App\Http\Resources\ComprasProveedores\ProveedorInternacionalResource;
use App\Models\ComprasProveedores\ProveedorInternacional;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Log;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class ProveedorInternacionalController extends Controller
{
    public string $entidad = 'Proveedor';
    private ArchivoService $archivoService;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->middleware('can:puede.ver.proveedores')->only('index', 'show');
        $this->middleware('can:puede.crear.proveedores')->only('store');
        $this->middleware('can:puede.editar.proveedores')->only('update');
        $this->middleware('can:puede.eliminar.proveedores')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = ProveedorInternacional::filter()->get();

        $results = ProveedorInternacionalResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProveedorInternacionalRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(ProveedorInternacionalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            $proveedor = ProveedorInternacional::create($datos);

            $modelo = new ProveedorInternacionalResource($proveedor);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        }catch (Exception $ex){
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ProveedorInternacional $proveedor
     * @return JsonResponse
     */
    public function show(ProveedorInternacional $proveedor)
    {
        $modelo = new ProveedorInternacionalResource($proveedor);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProveedorInternacionalRequest $request
     * @param ProveedorInternacional $proveedor
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(ProveedorInternacionalRequest $request, ProveedorInternacional $proveedor)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            $proveedor->update($datos);

            $modelo = new ProveedorInternacionalResource($proveedor);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        }catch (Exception $ex){
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProveedorInternacional $proveedor
     * @return Response
     * @throws ValidationException
     */
    public function destroy(ProveedorInternacional $proveedor)
    {
        throw ValidationException::withMessages(['error', 'No se puede eliminar el proveedor '.$proveedor->nombre.', método aún no configurado']);
    }

    /**
     * Listar archivos
     */
    public function indexFiles(ProveedorInternacional $proveedor)
    {
        try {
            $results = $this->archivoService->listarArchivos($proveedor);

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, ProveedorInternacional $proveedor)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($proveedor, $request->file, RutasStorage::PROVEEDORES_INTERNACIONALES->value . $proveedor->nombre);
            $mensaje = 'Archivo subido correctamente';
        } catch (Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            Log::channel('testing')->info('Log', ['Error en el storeFiles de ProveedorInternacionalController', $th->getMessage(), $th->getCode(), $th->getLine()]);
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
