<?php

namespace Src\App\ComprasProveedores;

use App\Models\ComprasProveedores\CriterioCalificacion;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use App\Models\ComprasProveedores\OfertaProveedor;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ProveedorService
{

    public static function datosProveedor($proveedor)
    {
        // Log::channel('testing')->info('Log', ['datosProveedor', $proveedor]);
        if ($proveedor->estado_calificado === Proveedor::CALIFICADO) {
            switch ($proveedor->calificacion) {
                case $proveedor->calificacion >= 90:
                    $categoria = 'Excelente';
                    $resumen = 'Cumple todos los requisitos para ser nuestro proveedor';
                    break;
                case $proveedor->calificacion > 80 && $proveedor->calificacion < 90:
                    $categoria = 'Muy bueno';
                    $resumen = 'Cumple la mayoria de requisitos para ser nuestro proveedor';
                    break;
                case $proveedor->calificacion > 70 && $proveedor->calificacion < 80:
                    $categoria = 'Bueno';
                    $resumen = 'Cumple con los estandares basicos requeridos para ser nuestro proveedor';
                    break;
                case $proveedor->calificacion > 50 && $proveedor->calificacion < 70:
                    $categoria = 'Poco eficiente';
                    $resumen = 'Notificar observaciones al proveedor para mejorar su situación';
                    break;
                default:
                    $categoria = 'Ineficiente';
                    $resumen = 'No cumple los requisitos basicos, por favor busca otro proveedor';
            }
        } else {
            $categoria = 'Por completar calificación';
            $resumen = 'Aún falta de calificar el proveedor';
        }
        return [$categoria, $resumen];
    }

    public static function filtrarProveedores($request)
    {
        $results = [];
        $results = Proveedor::when($request->razon_social, function ($query) use ($request) {
            $query->whereHas('empresa', function ($subQuery) use ($request) {
                $subQuery->where('razon_social', 'LIKE', '%' . $request->razon_social . '%');
            });
        })->when($request->canton, function ($query) use ($request) {
            $query->whereHas('parroquia.canton', function ($subQuery) use ($request) {
                $subQuery->where('id', $request->canton);
            });
        })->when($request->categorias, function ($query) use ($request) {
            $query->whereHas('categorias_ofertadas', function ($subQuery) use ($request) {
                $subQuery->whereIn('categoria_id', $request->categorias);
            });
        })->when($request->estado_calificado, function ($query) use ($request) {
            $query->whereIn('estado_calificado', $request->estado_calificado);
        })->where('estado', $request->estado)->get();
        // $results = Proveedor::with('empresa')->filter()->get();
        // Log::channel('testing')->info('Log', ['results', $results]);


        return $results;
    }

    public function empaquetarDatos($datos, string $var_ordenacion)
    {
        // Log::channel('testing')->info('Log', ['Datos antes de empaquetar', $datos]);
        $results = [];
        $cont = 0;
        foreach ($datos as $d) {
            $row['ruc'] = $d->empresa->identificacion;
            $row['razon_social'] = $d->empresa->razon_social;
            $row['ciudad'] = $d->empresa->canton?->canton;
            $row['establecimiento'] = $d->sucursal;
            $row['direccion'] = $d->direccion;
            $row['celular'] = $d->celular;
            $row['calificacion'] = $d->calificacion;
            $row['estado_calificado'] = $d->estado_calificado;
            $row['categorias'] = implode(', ',  $d->categorias_ofertadas->map(fn ($item) => $item->nombre)->toArray());
            $row['departamentos'] = implode(', ',  $d->departamentos_califican->map(fn ($item) => $item->nombre)->toArray());
            $results[$cont] = $row;
            $cont++;
        }

        usort($results, function ($a, $b) use ($var_ordenacion) {
            return $a[$var_ordenacion] <=> $b[$var_ordenacion]; //ordena de menor a mayor o de A a Z
            // return $b[$var_ordenacion] <=> $a[$var_ordenacion]; //ordena de mayor a menor o de Z a A
        });

        return $results;
    }

    public function empaquetarDatosContactos($datos, string $var_ordenacion)
    {
        // Log::channel('testing')->info('Log', ['Datos antes de empaquetar', $datos->unique('empresa_id')]);
        $results = [];
        $cont = 0;
        $datos = $datos->unique('empresa_id'); //se elimina los rucs repetidos, esto se realizado debido a que una razon social puede tener varias sucursales
        foreach ($datos as $d) {
            foreach ($d->empresa->contactos as $contacto) {
                $row['ruc'] = $d->empresa->identificacion;
                $row['razon_social'] = $d->empresa->razon_social;
                $row['nombres'] = $contacto->nombres;
                $row['apellidos'] = $contacto->apellidos;
                $row['celular'] = $contacto->celular;
                $row['correo'] = $contacto->correo;
                $row['tipo_contacto'] = $contacto->tipo_contacto;
                $results[$cont] = $row;
                $cont++;
            }
        }

        usort($results, function ($a, $b) use ($var_ordenacion) {
            return $a[$var_ordenacion] <=> $b[$var_ordenacion]; //ordena de menor a mayor o de A a Z
            // return $b[$var_ordenacion] <=> $a[$var_ordenacion]; //ordena de mayor a menor o de Z a A
        });

        // Log::channel('testing')->info('Log', ['Datos de contactos después de empaquetar', $results]);
        return $results;
    }
    public function empaquetarDatosBancariosProveedor($datos, string $var_ordenacion)
    {
        $datos = $datos->unique('empresa_id'); //se elimina los rucs repetidos, esto se realizado debido a que una razon social puede tener varias sucursales
        $results = [];
        $cont = 0;
        foreach ($datos as $d) {
            foreach ($d->empresa->datos_bancarios as $banco) {
                $row['ruc'] = $d->empresa->identificacion;
                $row['razon_social'] = $d->empresa->razon_social;
                $row['banco'] = $banco->banco->nombre;
                $row['tipo_cuenta'] = $banco->tipo_cuenta;
                $row['numero_cuenta'] = $banco->numero_cuenta;
                $row['identificacion'] = $banco->identificacion;
                $row['propietario_cuenta'] = $banco->nombre_propietario;
                $results[$cont] = $row;
                $cont++;
            }
        }

        usort($results, function ($a, $b) use ($var_ordenacion) {
            return $a[$var_ordenacion] <=> $b[$var_ordenacion]; //ordena de menor a mayor o de A a Z
            // return $b[$var_ordenacion] <=> $a[$var_ordenacion]; //ordena de mayor a menor o de Z a A
        });

        return $results;
    }

    public function empaquetarDatosCalificacionProveedor($proveedor)
    {
        $results = [];
        $cont = 0;
        // Log::channel('testing')->info('Log', ['ProveedorController -> reporteCalificacion', $proveedor->departamentos_califican()->get()]);
        foreach ($proveedor->departamentos_califican()->get() as $detalle) {
            // Log::channel('testing')->info('Log', ['Det', $detalle]); //este es el detalle_departamento_proveedor, de aqui obtendras la calificacion global y el empleado que califica
            $row['ruc']  = $proveedor->empresa->identificacion;
            $row['razon_social']  = $proveedor->empresa->razon_social;
            $row['sucursal']  = $proveedor->sucursal;
            $row['departamento']  = $detalle->nombre;
            $row['ciudad']  = $proveedor->parroquia?->canton->canton;
            $row['direccion']  = $proveedor->direccion;
            $row['categorias'] = implode(', ',  $proveedor->categorias_ofertadas->map(fn ($item) => $item->nombre)->toArray());
            $row['calificacion_total'] = $proveedor->calificacion;
            $detalle_departamentos = DetalleDepartamentoProveedor::find($detalle->pivot->id);
            $row['empleado'] = $detalle_departamentos->empleado?->nombres . ' ' . $detalle_departamentos->empleado?->apellidos;
            $row['calificacion'] = $detalle_departamentos->calificacion;
            $row['fecha_calificacion'] = $detalle_departamentos->fecha_calificacion;
            $calificaciones_bienes = [];
            $calificaciones_servicios = [];
            // Log:channel('testing')->info('Log', ['Detalle', $detalle_departamentos]); //este es el detalle_departamento_proveedor, de aqui obtendras la calificacion global y el empleado que califica
            foreach ($detalle_departamentos->calificaciones_criterios()->get() as $index => $criterio) {
                // Log::channel('testing')->info('Log', ['Criterio', $criterio]); //estas son las calificaciones del departamento en curso
                // Log::channel('testing')->info('Log', ['Pivote', $criterio->pivot]); //estas son las calificaciones del departamento en curso
                $calificacion['criterio'] = $criterio->nombre;
                $calificacion['comentario'] = $criterio->pivot->comentario;
                $calificacion['peso'] = $criterio->pivot->peso;
                $calificacion['puntaje'] = $criterio->pivot->puntaje;
                $calificacion['calificacion'] = $criterio->pivot->calificacion;
                if ($criterio->oferta->nombre == OfertaProveedor::BIENES)
                    $calificaciones_bienes[$index] = $calificacion;
                else $calificaciones_servicios[$index] = $calificacion;
            }
            $row['calificaciones_bienes'] = $calificaciones_bienes;
            $row['calificaciones_servicios'] = $calificaciones_servicios;
            $results[$cont] = $row;
            $cont++;
        }
        Log::channel('testing')->info('Log', ['ProveedorController -> reporteCalificacionProveedor->empaquetarDatos', $results]);

        return $results;
    }
}
