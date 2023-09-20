<?php

namespace Src\App\ComprasProveedores;

use App\Models\Proveedor;
use Illuminate\Support\Facades\Log;

class ProveedorService
{

    public static function filtrarProveedores($request)
    {
        $results = [];
        Log::channel('testing')->info('Log', ['ProveedorService->filtrarProveedoresPorRazonSocial', $request->all()]);
        $results = Proveedor::when($request->razon_social, function ($query) use ($request) {
            $query->whereHas('empresa', function ($subQuery) use ($request) {
                $subQuery->where('razon_social', 'LIKE', '%' . $request->razon_social . '%');
            });
        })->when($request->canton, function ($query) use ($request) {
            $query->whereHas('parroquia.canton', function ($subQuery) use ($request) {
                $subQuery->where('id', $request->canton);
            });
        })->when($request->categorias, function ($query) use ($request) {
            $query->whereHas('categorias_ofertadas', function ($subQuery) use ($request) {
                $subQuery->whereIn('categoria_id', $request->categorias);
            });
        })->where('estado', $request->estado)->get();
        // $results = Proveedor::with('empresa')->filter()->get();
        Log::channel('testing')->info('Log', ['results', $results]);


        return $results;
    }
}
