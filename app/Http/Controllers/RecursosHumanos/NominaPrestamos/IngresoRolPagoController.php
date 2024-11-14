<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\IngresoRolPagoRequest;
use App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago;
use Src\Shared\Utils;

class IngresoRolPagoController extends Controller
{
    private string $entidad = 'Ingreso Rol Pago';
    public function __construct()
    {
        $this->middleware('can:puede.ver.ingreso_rol_pago')->only('index', 'show');
        $this->middleware('can:puede.crear.ingreso_rol_pago')->only('store');
        $this->middleware('can:puede.editar.ingreso_rol_pago')->only('update');
        $this->middleware('can:puede.eliminar.ingreso_rol_pago')->only('destroy');
    }
    /**
     * Guardar
     */

    public function store(IngresoRolPagoRequest $request)
    {
        //Respuesta
        $datos = $request->validated();
        $modelo = IngresoRolPago::create($datos);
        //$modelo = new EgresoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
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
