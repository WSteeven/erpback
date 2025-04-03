<?php

namespace Src\App;

use App\Models\Notificacion;
use App\Models\User;

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
            ->orWhere('mensaje', 'LIKE', '%Devolución Aceptada%')
            ->orWhere('mensaje', 'LIKE', '%Han rechazado  devolucion  a%')
            ->orWhere('mensaje', 'LIKE', '%Han realizado una  devolucion  por un monto de $%')
            ->orWhere('mensaje', 'LIKE', '%Han anulado una  devolucion a%')
            ->orWhere('mensaje', 'LIKE', '%Acepto Transferencia%')
            ->orWhere('mensaje', 'LIKE', '%Han rechazado  transferencia de%')
            ->orWhere('mensaje', 'LIKE', '%Han realizado una  transferencia de%')
            ->orWhere('mensaje', 'LIKE', '%Han anulado una  transferencia de%')
            ->orWhere('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->limit(100)->get($campos);
        return $results;
    }

    public function obtenerNotificacionesRolVehiculos($campos)
    {
        $results = Notificacion::ignoreRequest(['campos'])
            ->where('mensaje', 'LIKE', '%debe cumplir con la matriculación vehicular anual durante este mes%')
            ->orWhere('mensaje', 'LIKE', '%no ha sido matriculado aún y está rezagado según el calendario de matriculación establecido%')
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
     * @return Notificacion[] una lista de notificaciones.
     */
    public function obtenerNotificacionesRol(string $rol, mixed $campos)
    {
        return match ($rol) {
            User::ROL_COORDINADOR_BODEGA => $this->obtenerNotificacionesRolCoordinadorBodega($campos),
            User::ROL_BODEGA => $this->obtenerNotificacionesRolBodega($campos),
            User::ROL_BODEGA_TELCONET => $this->obtenerNotificacionesRolBodegaTelconet($campos),
            User::ROL_CONTABILIDAD => $this->obtenerNotificacionesRolContabilidad($campos),
            User::ROL_COMPRAS => $this->obtenerNotificacionesRolCompras($campos),
            User::ROL_ADMINISTRADOR_VEHICULOS => $this->obtenerNotificacionesRolVehiculos($campos),
            default => Notificacion::ignoreRequest(['campos'])->where('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->get($campos),
        };
    }
}
