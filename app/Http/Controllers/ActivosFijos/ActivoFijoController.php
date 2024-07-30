<?php

namespace App\Http\Controllers\ActivosFijos;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivosFijos\ActivoFijoResource;
use App\Http\Resources\ActivosFijos\EntregaActivoFijoResource;
use App\Models\ActivosFijos\ActivoFijo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\App\InventarioService;
use Src\App\Sistema\PaginationService;

class ActivoFijoController extends Controller
{
    private $entidad = 'Activo fijo';
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
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
        $transacciones = $inventarioService->kardex($request['detalle_producto_id'], '2022-04-01 00:00:00', Carbon::now());
        $results = collect($transacciones['results'])->filter(fn ($transaccion) => $transaccion['tipo'] == 'EGRESO' && $transaccion['cliente_id'] == $request['cliente_id'])->values();
        $results = EntregaActivoFijoResource::collection($results);
        return response()->json(compact('results'));
    }
}
