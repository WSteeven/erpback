<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class IngresoRolPagoController extends Controller
{
    private $entidad = 'Ingreso Rol Pago';
    public function __construct()
    {
        $this->middleware('can:puede.ver.ingreso_rol_pago')->only('index', 'show');
        $this->middleware('can:puede.crear.ingreso_rol_pago')->only('store');
        $this->middleware('can:puede.editar.ingreso_rol_pago')->only('update');
        $this->middleware('can:puede.eliminar.ingreso_rol_pago')->only('update');
    }
     /**
     * Eliminar
     */
    public function destroy(IngresoRolPago $ingreso_rol_pago)
    {
        $ingreso_rol_pago->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
