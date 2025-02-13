<?php

namespace Src\App\ComprasProveedores;

use App\Models\ComprasProveedores\Proforma;
use Src\Config\Autorizaciones;
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
                return Proforma::where(function ($query) {
                    $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id);
                })->where('estado_id', EstadosTransacciones::PENDIENTE)->ignoreRequest(['solicitante_id', 'autorizador_id',])->filter()->get();
            case 3:
                return Proforma::where(function ($query) {
                    $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id);
                })->orWhere('estado_id', EstadosTransacciones::ANULADA)->ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
            case 4:
                return Proforma::where(function ($query) {
                    $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id);
                })->where('estado_id', EstadosTransacciones::COMPLETA)->ignoreRequest(['solicitante_id', 'autorizador_id', 'autorizacion_id'])->filter()->get();
            case 5: //cuando esta aprobada la autorizacion pero se anula el prefacturado
                return Proforma::where(function ($query) {
                    $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id);
                })->where('estado_id', EstadosTransacciones::ANULADA)->where('autorizacion_id', Autorizaciones::APROBADO)->ignoreRequest(['solicitante_id', 'autorizador_id', 'autorizacion_id'])->filter()->get();
            default:
                return Proforma::where(function ($query) {
                    $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id);
                })->ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
        }
    }

    public static function filtrarProformasAdministrador($request)
    {

        switch ($request->autorizacion_id) {
            case 2:
                return Proforma::where('estado_id', EstadosTransacciones::PENDIENTE)->ignoreRequest(['solicitante_id', 'autorizador_id',])->filter()->get();
            case 3: //cuando esta cancelada la autorizacion o cuando está anulada
                return Proforma::ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->where('estado_id', EstadosTransacciones::ANULADA)->get();
            case 4: //cuando esta completa porque se asocio una prefactura
                return Proforma::where('estado_id', EstadosTransacciones::COMPLETA)->ignoreRequest(['solicitante_id', 'autorizador_id', 'autorizacion_id'])->filter()->get();
            case 5: //cuando esta aprobada la autorizacion pero se anula el prefacturado
                return Proforma::where('estado_id', EstadosTransacciones::ANULADA)->where('autorizacion_id', Autorizaciones::APROBADO)->ignoreRequest(['solicitante_id', 'autorizador_id', 'autorizacion_id'])->filter()->get();
            default:
                return Proforma::ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
        }
    }

    public static function filtrarProformasJefeTecnico($request)
    {
        $ids_buscar = [30, 27];//3=Jonathan Veintimilla, 27=Joao Celi
        $query = Proforma::where(function ($q) use ($ids_buscar) {
            $q->orWhereIn('solicitante_id', $ids_buscar)
                ->orWhereIn('autorizador_id', $ids_buscar);
        });
        switch ($request->autorizacion_id) {
            case 2:
                return $query->where('estado_id', EstadosTransacciones::PENDIENTE)->ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
            case 3:
                return $query->Where('estado_id', EstadosTransacciones::ANULADA)->ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
            case 4:
                return
                    $query->where('estado_id', EstadosTransacciones::COMPLETA)->ignoreRequest(['solicitante_id', 'autorizador_id', 'autorizacion_id'])->filter()->get();
            case 5: //cuando esta aprobada la autorizacion pero se anula el prefacturado
                return $query->where('estado_id', EstadosTransacciones::ANULADA)
                    ->where('autorizacion_id', Autorizaciones::APROBADO)->ignoreRequest(['solicitante_id', 'autorizador_id', 'autorizacion_id'])->filter()->get();
            default:
                return $query->ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
        }
    }
}
