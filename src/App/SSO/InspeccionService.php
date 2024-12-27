<?php

namespace Src\App\SSO;

use App\Http\Requests\SSO\SeguimientoIncidenteRequest;
use App\Models\SSO\SeguimientoIncidente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InspeccionService
{
    /**
     * @throws \Throwable
     */
    public function ejecutar($request)
    {
        // Log::channel('testing')->info('Log', ['Request de seguimiento:', $request]);
        return DB::transaction(function () use ($request) {
            $request->validateResolved();
            return SeguimientoIncidente::create($request->validated());
        });
    }

    function realizarPedido()
    {
        //
    }

    function solicitarDescuento()
    {
        //
    }
}
