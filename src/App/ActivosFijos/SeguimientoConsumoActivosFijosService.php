<?php

namespace Src\App\ActivosFijos;

use App\Models\ActivosFijos\SeguimientoConsumoActivosFijos;
use App\Models\MaterialEmpleado;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\Sistema\PaginationService;

class SeguimientoConsumoActivosFijosService
{
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }
    /**
     * Devuelve el historial de consumo de un activo fijo ($detalle_producto_id, $cliente_id)
     * @param int $detalle_producto_id
     * @param int $cliente_id
     */
    public function seguimientoConsumoActivosFijos() //: Collection
    {
        $search = request('search');
        $paginate = request('paginate');

        if ($search) $query = SeguimientoConsumoActivosFijos::search($search); //->latest();
        else $query = SeguimientoConsumoActivosFijos::ignoreRequest(['paginate'])->filter()->latest();

        if ($paginate) return $this->paginationService->paginate($query, 100, request('page'));
        else return $query->get();
    }

    public function actualizarStockActivoFijoOcupado($request)
    {
        $materialEmpleado = MaterialEmpleado::where('empleado_id', Auth::user()->empleado->id)->where('detalle_producto_id', $request['detalle_producto_id'])->where('cliente_id', $request['cliente_id'])->first();
        if (!$materialEmpleado) throw ValidationException::withMessages(['404' => 'No se puede actualizar el stock porque no cuenta con este activo fijo.']);

        $materialEmpleado->cantidad_stock += (isset($request['cantidad_anterior']) ? $request['cantidad_anterior'] : 0)  - $request['cantidad_utilizada'];
        $materialEmpleado->save();
    }
}
