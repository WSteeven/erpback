<?php

namespace Src\App\ComprasProveedores;

use App\Models\ComprasProveedores\Proforma;
use Src\Config\EstadosTransacciones;

class ProformaService
{
    public function __construct()
    {
        //
    }

    public static function filtrarProformasEmpleado($request)
    {
        switch ($request->autorizacion_id) {
            case 2:
                $results = Proforma::where(function ($query) {
                    $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id);
                })->where('estado_id', EstadosTransacciones::PENDIENTE)->ignoreRequest(['solicitante_id', 'autorizador_id',])->filter()->get();
                return $results;
            case 3:
                $results = Proforma::where(function ($query) {
                    $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id);
                })->orWhere('estado_id', EstadosTransacciones::ANULADA)->ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
                return $results;
            case 4:
                $results = Proforma::where(function ($query) {
                    $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id);
                })->where('estado_id', EstadosTransacciones::COMPLETA)->ignoreRequest(['solicitante_id', 'autorizador_id', 'autorizacion_id'])->filter()->get();
                return $results;
            default:
                $results = Proforma::where(function ($query) {
                    $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id);
                })->ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
                return $results;
        }
    }

    public static function filtrarProformasAdministrador($request)
    {

        switch ($request->autorizacion_id) {
            case 2:
                $results = Proforma::where('estado_id', EstadosTransacciones::PENDIENTE)->ignoreRequest(['solicitante_id', 'autorizador_id',])->filter()->get();
                return $results;
            case 3://cuando esta cancelada la autorizacion o cuando está anulada
                $results = Proforma::ignoreRequest(['solicitante_id', 'autorizador_id', 'autorizacion_id'])->filter()->orWhere('estado_id', EstadosTransacciones::ANULADA)->get();
                return $results;
            case 4: //cuando esta completa porque se asocio una prefactura
                $results = Proforma::where('estado_id', EstadosTransacciones::COMPLETA)->ignoreRequest(['solicitante_id', 'autorizador_id', 'autorizacion_id'])->filter()->get();
                return $results;
            default:
                $results = Proforma::ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
                return $results;
        }
    }
}
