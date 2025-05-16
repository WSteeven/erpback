<?php

namespace App\Http\Controllers\ActivosFijos;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivosFijos\ActivoFijoRequest;
use App\Http\Resources\ActivosFijos\ActivoFijoResource;
use App\Http\Resources\ActivosFijos\EntregaActivoFijoResource;
use App\Models\ActivosFijos\ActivoFijo;
use App\Models\ConfiguracionGeneral;
use App\Models\TransaccionBodega;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\ActivosFijos\ControlActivoFijoService;
use Src\App\InventarioService;
use Src\App\Sistema\PaginationService;
use Src\App\Tareas\ProductoEmpleadoService;
use Src\Shared\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ActivoFijoController extends Controller
{
    protected PaginationService $paginationService;
    protected ProductoEmpleadoService $productoEmpleadoService;
    protected ControlActivoFijoService $controlActivoFijoService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
        $this->productoEmpleadoService = new ProductoEmpleadoService();
        $this->controlActivoFijoService = new ControlActivoFijoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection|BinaryFileResponse
     */
    public function index()
    {
        $search = request('search');
        $paginate = request('paginate');

        if (request('export')) return $this->controlActivoFijoService->descargarReporte();

        if(request('search')) $query = ActivoFijo::search($search);
        else $query = ActivoFijo::query();

        if ($paginate) $paginated = $this->paginationService->paginate($query, 100, request('page'));
        else $paginated = $query->get();

        return ActivoFijoResource::collection($paginated);
    }


    /**
     * Display the specified resource.
     *
     * @param ActivoFijo $activo_fijo
     * @return JsonResponse
     */
    public function show(ActivoFijo $activo_fijo)
    {
        $modelo = new ActivoFijoResource($activo_fijo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ActivoFijoRequest $request
     * @param ActivoFijo $activo_fijo
     * @return Response
     * @throws Throwable
     */
    public function update(ActivoFijoRequest $request, ActivoFijo $activo_fijo)
    {
        return DB::transaction(function () use ($request, $activo_fijo) {
            $datos = $request->validated();

            $activo_fijo->update($datos);
            $modelo = new ActivoFijoResource($activo_fijo->refresh());
            $mensaje = Utils::obtenerMensaje('Registro activo fijo', 'update');
            return response()->json(compact('mensaje', 'modelo'));
        });
    }


    /**
     * @throws ValidationException
     */
    public function entregas(Request $request)
    {
        $inventarioService = new InventarioService();
        $transacciones = $inventarioService->kardex($request['detalle_producto_id'], '2022-04-01 00:00:00', Carbon::now()->addDay());
        $results = collect($transacciones['results'])->filter(fn($transaccion) => $transaccion['tipo'] == 'EGRESO' && $transaccion['cliente_id'] == $request['cliente_id'] && in_array($transaccion['estado_comprobante'], [TransaccionBodega::PARCIAL, TransaccionBodega::ACEPTADA]))->values(); // && !!$transaccion['comprobante_firmado'])->values();
        $results = EntregaActivoFijoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function obtenerAsignacionesProductos()
    {
        request()->validate([
            'detalle_producto_id' => 'required|numeric|integer|exists:detalles_productos,id',
            'cliente_id' => 'required|numeric|integer|exists:clientes,id',
            'resumen_seguimiento' => 'nullable|boolean',
            'seguimiento' => 'nullable|boolean',
        ]);

        $results = $this->productoEmpleadoService->obtenerProductosPorDetalleCliente(request('detalle_producto_id'), request('cliente_id')); //, request('seguimiento'), request('resumen_seguimiento'));
        return response()->json(compact('results'));
    }

    public function obtenerActivosFijosAsignados()
    {
        // if (!request('empleado_id')) {
        /* throw ValidationException::withMessages([
                'empleado_id' => ['El campo empleado_id es requerido'],
            ]); */
        // }

        $results = $this->productoEmpleadoService->obtenerActivosFijosAsignados(request('empleado_id'));
        return response()->json(compact('results'));
    }

    public function printLabel(Request $request)
    {
        $template = file_get_contents(storage_path('app/design2.prn'));
        // $template = file_get_contents(storage_path('app/label_template.zpl'));
        // $template = file_get_contents(storage_path('app/100x50-QR.prn'));

        $activoFijo = ActivoFijo::find($request['id']);

        $detalleProducto = $activoFijo->detalleProducto;

        $configuracion = ConfiguracionGeneral::first();

        $replacements = [
            '{CODIGO_QR}' => str_pad($activoFijo->id, 6, '0', STR_PAD_LEFT),
            '{NOMBRE_EMPRESA}' => $configuracion->razon_social,
            '{TIPO_PRODUCTO}' => $detalleProducto->producto->nombre,
            '{MARCA}' => $detalleProducto->modelo->marca?->nombre,
            '{MODELO}' => $detalleProducto->modelo?->nombre,
            '{SERIE}' => $detalleProducto->serial,
            '{FECHA_COMPRA}' => TransaccionBodega::obtenerFechaCompraDetalle($detalleProducto->id),
            '{CODIGO_BARRAS}' => str_pad($activoFijo->id, 6, '0', STR_PAD_LEFT),
        ];

        // Log::channel('testing')->info('Log', ['Replacement ', $replacements]);

        $zpl = str_replace(array_keys($replacements), array_values($replacements), $template);

        return response($zpl, 200)->header('Content-Type', 'text/plain');
    }
}
