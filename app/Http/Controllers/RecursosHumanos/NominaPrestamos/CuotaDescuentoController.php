<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecursosHumanos\NominaPrestamos\CuotaDescuento;
use Carbon\Carbon;
use Src\Shared\Utils;

class CuotaDescuentoController extends Controller
{
    private string $entidad = 'Cuota';



    public function aplazarCuotaDescuento(Request $request, CuotaDescuento $cuota)
    {
        // primero obtenemos la ultima cuota de todas para actualizar al mes siguiente la actual
        $ultima_cuota = CuotaDescuento::where('descuento_id', $cuota->descuento_id)->orderBy('mes_vencimiento', 'desc')->first();
        // Eliminamos algun egreso relacionado a la cuota actual para eliminarlo también del rol de pagos del mes actual
        $cuota->egreso_rol_pago()->delete();
        // al mes de vencimiento de la cuota actual le daremos un mes más del mes de vencimiento de la ultima cuota
        $ultimo_mes = Carbon::createFromFormat('Y-m', $ultima_cuota->mes_vencimiento);
        $cuota->mes_vencimiento = $ultimo_mes->addMonth()->format('Y-m');
        $cuota->comentario = $request->comentario;
        $cuota->save();

        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'cuota'));
    }

}
