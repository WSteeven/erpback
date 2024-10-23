<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\TransaccionBodega;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class TransaccionBodegaController extends Controller
{
    private string $entidad = 'Transaccion';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->middleware('can:puede.ver.transacciones')->only('index', 'show');
        $this->middleware('can:puede.crear.transacciones')->only('store');
        $this->middleware('can:puede.editar.transacciones')->only('update');
        $this->middleware('can:puede.eliminar.transacciones')->only('destroy');
        $this->archivoService = new ArchivoService();
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = TransaccionBodegaResource::collection(TransaccionBodega::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     * @throws ValidationException
     */
    public function store(TransaccionBodegaRequest $request)
    {
        throw ValidationException::withMessages(['Error'=> 'Método no configurado']);
    }


    /**
     * Consultar
     */
    public function show(TransaccionBodega $transaccion)
    {
        $modelo = new TransaccionBodegaResource($transaccion);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     * @throws ValidationException
     */
    public function update(TransaccionBodegaRequest $request, TransaccionBodega  $transaccion)
    {
        throw ValidationException::withMessages(['Error'=> 'Método no configurado']);
    }

    /**
     * Eliminar
     * @throws ValidationException
     */
    public function destroy(TransaccionBodega $transaccion)
    {
        throw ValidationException::withMessages(['Error'=> 'Método no configurado']);
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, TransaccionBodega $transaccion_bodega)
    {
        Log::channel('testing')->info('Log', ['Request: ', $request['tipo']]);
        try {
            $results = $this->archivoService->listarArchivos($transaccion_bodega);
        } catch (Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     * @throws ValidationException|Throwable
     */
    public function storeFiles(Request $request, TransaccionBodega $transaccion_bodega)
    {
        if (!$request['tipo']) throw ValidationException::withMessages(['tipo' => ['Debe proporcionar un tipo de archivo.']]);

        try {
            $ruta = match ($request['tipo']) {
                TransaccionBodega::JUSTIFICATIVO_USO => RutasStorage::ACTIVOS_FIJOS_JUSTIFICATIVO_USO->value,
                TransaccionBodega::ACTA_ENTREGA_RECEPCION => RutasStorage::ACTIVOS_FIJOS_ACTA_ENTREGA_RECEPCION->value,
            };
            $modelo  = $this->archivoService->guardarArchivo($transaccion_bodega, $request->file, $ruta, $request['tipo']);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
