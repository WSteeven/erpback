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
        $results = Proveedor::whereHas('empresa', function ($query) use ($request) {
            $query->where('razon_social', 'LIKE', '%' . $request->razon_social . '%');
        })->whereHas('parroquia.canton', function ($query) use ($request) {
            $query->where('id', $request->canton);
        })->where('estado', $request->estado)->get();
        // $results = Proveedor::with('empresa')->filter()->get();
        Log::channel('testing')->info('Log', ['results', $results]);


        return $results;
    }
}
