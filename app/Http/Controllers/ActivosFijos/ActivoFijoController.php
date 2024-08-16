<?php

namespace App\Http\Controllers\ActivosFijos;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivosFijos\ActivoFijoResource;
use App\Http\Resources\ActivosFijos\EntregaActivoFijoResource;
use App\Models\ActivosFijos\ActivoFijo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Src\App\InventarioService;
use Src\App\Sistema\PaginationService;
use Src\App\Tareas\ProductoEmpleadoService;

class ActivoFijoController extends Controller
{
    private $entidad = 'Activo fijo';
    protected PaginationService $paginationService;
    protected ProductoEmpleadoService $productoEmpleadoService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
        $this->productoEmpleadoService = new ProductoEmpleadoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        $paginate = request('paginate');

        $query = ActivoFijo::search($search);

        if ($paginate) $paginated = $this->paginationService->paginate($query, 100, request('page'));
        else $paginated = $query->get();

        return ActivoFijoResource::collection($paginated);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ActivoFijo $activo_fijo)
    {
        $modelo = new ActivoFijoResource($activo_fijo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function entregas(Request $request)
    {
        $inventarioService = new InventarioService();
        $transacciones = $inventarioService->kardex($request['detalle_producto_id'], '2022-04-01 00:00:00', Carbon::now()->addDay(1));
        $results = collect($transacciones['results'])->filter(fn ($transaccion) => $transaccion['tipo'] == 'EGRESO' && $transaccion['cliente_id'] == $request['cliente_id'] && !!$transaccion['comprobante_firmado'])->values();
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
}
