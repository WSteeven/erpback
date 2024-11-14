<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\EgresoRolPagoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\EgresoRolPagoResource;
use App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales;
use App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use Src\Shared\Utils;

class EgresoRolPagoController extends Controller
{
    private string $entidad = 'Egreso Rol Pago';
    public function __construct()
    {
        $this->middleware('can:puede.ver.egreso_rol_pago')->only('index', 'show');
        $this->middleware('can:puede.crear.egreso_rol_pago')->only('store');
        $this->middleware('can:puede.editar.egreso_rol_pago')->only('update');
        $this->middleware('can:puede.eliminar.egreso_rol_pago')->only('destroy');
    }
    /**
     * Guardar
     */

     public function store(EgresoRolPagoRequest $request)
     {
         //Respuesta
         $datos = $request->validated();
         $rolPago = RolPago::where('id',$datos['id_rol_pago'])->first();
//         $tipo = null;
         $entidad_descuento = null;
            switch ($datos['tipo']) {
                case 'DESCUENTO_GENERAL':
//                    $tipo = 'App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales';
                    $entidad_descuento = DescuentosGenerales::find($datos['descuento_id']);
                    break;
                case 'MULTA':
//                    $tipo = 'App\Models\RecursosHumanos\NominaPrestamos\Multas';
                    $entidad_descuento = Multas::find($datos['descuento_id']);
                    break;
            }
        $modelo= EgresoRolPago::crearEgresoRol($rolPago,$datos['monto'],$entidad_descuento);
         $modelo = new EgresoRolPagoResource($modelo);
         $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

         // event(new PruebaEvent("Se ha creado una categoria nueva"));
         return response()->json(compact('mensaje', 'modelo'));
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
