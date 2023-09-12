<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpresaRequest;
use App\Http\Resources\EmpresaResource;
use App\Models\Empresa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class EmpresaController extends Controller
{
    private $entidad = 'Empresa';
    private $archivoService;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->middleware('can:puede.ver.empresas')->only('index', 'show');
        $this->middleware('can:puede.crear.empresas')->only('store');
        $this->middleware('can:puede.editar.empresas')->only('update');
        $this->middleware('can:puede.eliminar.empresas')->only('destroy');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = [];

        $results = Empresa::filter()->orderBy('razon_social', 'asc')->get();
        $results = EmpresaResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(EmpresaRequest $request)
    {
        // Adaptación de foreign keys
        $datos = $request->validated();
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
        //Respuesta
        $modelo = Empresa::create($datos);
        $modelo = new EmpresaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Empresa $empresa)
    {
        $modelo = new EmpresaResource($empresa);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(EmpresaRequest $request, Empresa  $empresa)
    {
        // Adaptación de foreign keys
        Log::channel('testing')->info('Log', ['Antes de validar', $request->all()]);
        $datos = $request->validated();
        Log::channel('testing')->info('Log', ['Despues de validar', $request->all()]);
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

        //Respuesta
        $empresa->update($datos);
        $modelo = new EmpresaResource($empresa->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, Empresa $empresa)
    {
        $results = [];

        $results = [];
        // Log::channel('testing')->info('Log', ['Recibido del front en indexFiles de empresas', $request->all(), $empresa]);
        // Log::channel('testing')->info('Log', [' indexFiles de empresas', $empresa]);
        try {
            // $detalle_dept = DetalleDepartamentoProveedor::find($detalle);
            if ($empresa) {
                $results = $empresa->archivos()->get();
            }

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, Empresa $empresa)
    {
        // Log::channel('testing')->info('Log', ['Recibido del front en storeFiles', $request->all(), $empresa]);
        $modelo = [];
        try {
            if ($request->allFiles()) {
                foreach ($request->allFiles() as $archivo) {
                    $archivo = $this->archivoService->guardar($empresa, $archivo, RutasStorage::EMPRESAS->value . $empresa->identificacion . '/');
                    array_push($modelo, $archivo);
                    // $archivo = $this->archivoService->guardar($empresa, $archivo, 'public/empresas/'.$empresa->identificacion.'/');
                }
            }

            $mensaje = 'Archivo subido correctamente';
        } catch (\Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            Log::channel('testing')->info('Log', ['Error en el storeFiles de EmpresaController', $th->getMessage(), $th->getCode(), $th->getLine()]);
            return response()->json(compact('mensaje'));
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
