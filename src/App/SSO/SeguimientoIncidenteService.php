<?php

namespace Src\App\SSO;

use App\Http\Requests\SSO\SeguimientoIncidenteRequest;
use App\Models\SSO\SeguimientoIncidente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeguimientoIncidenteService
{
    /**
     * @throws \Throwable
     */
    public function createSeguimientoIncidente($datos)
    {
//        Log::channel('testing')->info('Log', ['Request de seguimiento:', $request]);
        return DB::transaction(function () use ($datos) {
//            $request->validateResolved();
            return SeguimientoIncidente::create($datos);
        });
    }

    function realizarPedido()
    {
        // DEVOLUCIONES APROBADOS PENDIENTES
    }

    function solicitarDescuento()
    {
        //
    }
}
