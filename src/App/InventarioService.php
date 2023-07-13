<?php

namespace Src\App;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioService
{

    /**
     * La función toma un término de búsqueda de una solicitud y devuelve artículos de inventario con
     * una descripción coincidente, filtrando opcionalmente artículos con cantidad cero.
     * 
     * @param Request request El parámetro  es una instancia de la clase Request, que
     * representa una solicitud HTTP realizada al servidor. Contiene información sobre la solicitud,
     * como el método de solicitud, la URL, los encabezados y cualquier dato enviado con la solicitud.
     * 
     * @return results los resultados de búsqueda basados en la solicitud dada.
     */
    public static function search(Request $request)
    {
        $search = $request->search;
        $results = $request->boolean('zeros') ? Inventario::with('detalle')
            ->whereHas('detalle', function ($query) use ($search) {
                $query->where('descripcion', 'LIKE', '%' . $search . '%');
                $query->orWhere('serial', 'LIKE', '%' . $search . '%');
            })
            ->when($request->cliente_id, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente_id);
            })
            ->when($request->sucursal_id, function ($query) use ($request) {
                $query->where('sucursal_id', $request->sucursal_id);
            })
            ->when($request->condicion_id, function ($query) use ($request) {
                $query->where('condicion_id', $request->condicion_id);
            })
            ->get() :
            Inventario::with('detalle')->whereHas('detalle', function ($query) use ($search) {
                $query->where('descripcion', 'LIKE', '%' . $search . '%');
                $query->orWhere('serial', 'LIKE', '%' . $search . '%');
            })->when($request->cliente_id, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente_id);
            })
            ->when($request->sucursal_id, function ($query) use ($request) {
                $query->where('sucursal_id', $request->sucursal_id);
            })
            ->when($request->condicion_id, function ($query) use ($request) {
                $query->where('condicion_id', $request->condicion_id);
            })->where('cantidad', '<>', 0)->get();
        return $results;
    }

    /**
     * La función recupera artículos de inventario en función de ciertas condiciones, con una opción
     * para incluir artículos con cantidad cero.
     * 
     * @param Request request El parámetro  es una instancia de la clase Request, que se
     * utiliza para recuperar datos de la solicitud HTTP. Contiene información como el método de
     * solicitud, los encabezados y los datos de entrada. En este caso, se utiliza para recuperar
     * parámetros de consulta como 'ceros', 'cliente_id', 'sucursal_id', y 'condicion_id'.
     * 
     * @return Collection results una colección de resultados de la base de datos. Los resultados se filtran en función de
     * los valores de los parámetros `cliente_id`, `sucursal_id` y `condicion_id` de la solicitud. Si
     * el parámetro `ceros` es verdadero, se devuelven todos los resultados. Si `ceros` es falso, solo
     * se devuelven resultados con un valor de `cantidad` distinto de 0.
     */
    public static function todos(Request $request)
    {
        $results = $request->boolean('zeros') ?
            Inventario::when($request->cliente_id, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente_id);
            })
            ->when($request->sucursal_id, function ($query) use ($request) {
                $query->where('sucursal_id', $request->sucursal_id);
            })
            ->when($request->condicion_id, function ($query) use ($request) {
                $query->where('condicion_id', $request->condicion_id);
            })
            ->get() :
            Inventario::when($request->cliente_id, function ($query) use ($request) {
                $query->where('cliente_id', $request->cliente_id);
            })
            ->when($request->sucursal_id, function ($query) use ($request) {
                $query->where('sucursal_id', $request->sucursal_id);
            })
            ->when($request->condicion_id, function ($query) use ($request) {
                $query->where('condicion_id', $request->condicion_id);
            })->where('cantidad', '<>', 0)->get();
        return $results;
    }
}
