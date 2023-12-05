<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class EgresoRolPagoController extends Controller
{
    private $entidad = 'Egreso Rol Pago';
    public function __construct()
    {
        $this->middleware('can:puede.ver.egreso_rol_pago')->only('index', 'show');
        $this->middleware('can:puede.crear.egreso_rol_pago')->only('store');
        $this->middleware('can:puede.editar.egreso_rol_pago')->only('update');
        $this->middleware('can:puede.eliminar.egreso_rol_pago')->only('update');
    }
     /**
     * Eliminar
     */
    public function destroy(EgresoRolPago $egreso_rol_pago)
    {
        $egreso_rol_pago->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
