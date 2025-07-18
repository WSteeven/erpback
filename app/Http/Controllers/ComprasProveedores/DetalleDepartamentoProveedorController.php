<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComprasProveedores\DetalleDepartamentoProveedorResource;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\App\ArchivoService;
use Src\Shared\Utils;

class DetalleDepartamentoProveedorController extends Controller
{
    private string $entidad = 'Calificación';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->archivoService = new ArchivoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = DetalleDepartamentoProveedorResource::collection(DetalleDepartamentoProveedor::filter()->get());
        return response()->json(compact('results'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida', $request->all()]);
        $detalle = DetalleDepartamentoProveedor::where('departamento_id', auth()->user()->empleado->departamento_id)->where('proveedor_id', $request->proveedor_id)->first();
        $detalle->update([
            'calificacion' => $request->calificacion,
            'empleado_id' => auth()->user()->empleado->id,
            'fecha_calificacion' => date("Y-m-d h:i:s"),
        ]);
        $modelo = $detalle->refresh();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Listar todos los archivos de un determinado detalle
     */
    public function indexFiles($detalle)
    {
        $results = [];
        // Log::channel('testing')->info('Log', ['Recibido del front en indexFiles en detalleDeptProvControler', $request->all(), $detalle]);
        try {
            $detalle_dept = DetalleDepartamentoProveedor::find($detalle);
            if ($detalle_dept) $results = $this->archivoService->listarArchivos($detalle_dept);

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'));
        }
    }
}
