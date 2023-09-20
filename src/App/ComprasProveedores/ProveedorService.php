<?php

namespace Src\App\ComprasProveedores;

use App\Models\Proveedor;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ProveedorService
{

    public static function filtrarProveedores($request)
    {
        $results = [];
        // Log::channel('testing')->info('Log', ['ProveedorService->filtrarProveedoresPorRazonSocial', $request->all()]);
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
        Log::channel('testing')->info('Log', ['Datos antes de empaquetar', $datos]);
        $results = [];
        $cont = 0;
        foreach ($datos as $d) {
            $row['ruc'] = $d->empresa->identificacion;
            $row['razon_social'] = $d->empresa->razon_social;
            $row['ciudad'] = $d->empresa->canton?->canton;
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
}
