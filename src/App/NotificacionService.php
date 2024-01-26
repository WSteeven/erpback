<?php

namespace Src\App;

use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificacionService
{
    public function __construct()
    {
    }
    public function obtenerNotificacionesRolCoordinadorBodega($campos)
    {

        $results = $this->obtenerNotificacionesRolBodega($campos);
        $results2 = Notificacion::ignoreRequest(['campos'])
            ->where('mensaje', 'LIKE', '%Preorden de compra N°%')
            ->orWhere('mensaje', 'LIKE', '%Por favor establece precios y proveedor para que la orden de compra pueda ser impresa%')
            ->orWhere('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->limit(100)->get($campos);
        $results3 = Notificacion::ignoreRequest(['campos'])->where('mensaje', 'LIKE', '%Estimado/a bodeguero, tienes%')->filter()->orderBy('id', 'desc')->limit(100)->get($campos);
        $results->push(...$results2);
        return $results3->merge($results);
    }
    /**
     * La función "obtenerNotificacionesRolBodega" recupera notificaciones en función del rol BODEGA
     * y campos especificados.
     *
     * @param mixed $campos El parámetro "campos" se utiliza para especificar los campos/columnas que se deben
     * recuperar del modelo "Notificación". Es un parámetro opcional y puede ser una matriz de nombres
     * de campo o una cadena de nombres de campo separados por comas.
     *
     * @return Illuminate\Database\Eloquent\Collection $results una colección de objetos de Notificación.
     */
    public function obtenerNotificacionesRolBodega($campos)
    {
        if (!$campos[0] === '') {
            $results = Notificacion::ignoreRequest(['campos'])
                ->where('mensaje', 'LIKE', '%pedido recién autorizado en la sucursal%')
                ->whereNot('mensaje', 'LIKE', '%telconet%')
                ->orWhere('mensaje', 'LIKE', '%Hay una devolución recién autorizada en la ciudad%')
                ->orWhere('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->limit(100)->get($campos);
        } else {
            $results = Notificacion::where('mensaje', 'LIKE', '%pedido recién autorizado en la sucursal%')
                ->whereNot('mensaje', 'LIKE', '%telconet%')
                ->orWhere('mensaje', 'LIKE', '%Hay una devolución recién autorizada en la ciudad%')
                ->orWhere('per_destinatario_id', auth()->user()->empleado->id)->ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        }

        return $results;
    }
    public function obtenerNotificacionesRolBodegaTelconet($campos)
    {
        if (!$campos[0] === '') {
            $results = Notificacion::ignoreRequest(['campos'])
                ->where('mensaje', 'LIKE', '%telconet%')
                ->orWhere('mensaje', 'LIKE', '%Hay una devolución recién autorizada en la ciudad%')
                ->orWhere('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->limit(100)->get($campos);
        } else {
            $results = Notificacion::where('mensaje', 'LIKE', '%telconet%')
                ->orWhere('mensaje', 'LIKE', '%Hay una devolución recién autorizada en la ciudad%')
                ->orWhere('per_destinatario_id', auth()->user()->empleado->id)->ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        }

        return $results;
    }
    public function obtenerNotificacionesRolCompras($campos)
    {
        $results = Notificacion::ignoreRequest(['campos'])
            ->orWhere('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->limit(100)->get($campos);

        return $results;
    }

    public function obtenerNotificacionesRolContabilidad($campos)
    {
        $results = Notificacion::ignoreRequest(['campos'])
            ->where('mensaje', 'LIKE', '%ha realizado un ingreso de materiales con motivo COMPRA A PROVEEDOR en la sucursal%')
            ->orWhere('mensaje', 'LIKE', '%ha marcado como realizada la Orden de Compra%')
            ->orWhere('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->limit(100)->get($campos);
        return $results;
    }

    /**
     * La función "obtenerNotificacionesRol" recupera notificaciones en función del rol del usuario y
     * campos especificados.
     *
     * @param string $rol El parámetro "rol" representa el rol de un usuario. Puede tener valores como "bodega"
     * o "compras" que corresponden a roles específicos en el sistema.
     * @param mixed $campos El parámetro "campos" se utiliza para especificar los campos o columnas que desea
     * recuperar de la base de datos. Es un parámetro opcional y se puede utilizar para limitar la
     * cantidad de datos devueltos en los resultados.
     *
     * @return Illuminate\Database\Eloquent\Collection una lista de notificaciones.
     */
    public function obtenerNotificacionesRol($rol, $campos)
    {
        $results = [];
        switch ($rol) {
            case User::ROL_COORDINADOR_BODEGA:
                $results = $this->obtenerNotificacionesRolCoordinadorBodega($campos);
                break;
            case User::ROL_BODEGA:
                $results = $this->obtenerNotificacionesRolBodega($campos);
                break;
            case User::ROL_BODEGA_TELCONET:
                $results = $this->obtenerNotificacionesRolBodegaTelconet($campos);
                break;
            case User::ROL_CONTABILIDAD:

                $results = $this->obtenerNotificacionesRolContabilidad($campos);
                break;
            case User::ROL_COMPRAS:
                $results = $this->obtenerNotificacionesRolCompras($campos);
                break;
            default:
                $results = Notificacion::ignoreRequest(['campos'])->where('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->get($campos);
        }

        return $results;
    }
}
