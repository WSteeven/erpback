<?php

namespace Src\App\Vehiculos;

use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\Request;

class VehiculoService
{

    public function __construct()
    {
    }

    public function obtenerHistorial(Vehiculo $vehiculo, Request $request)
    {
        $results = [];
        if (count($request->opciones) == 1) {
            switch ($request->opciones[0]) {
                case Vehiculo::MANTENIMIENTOS:
                    

                    break;
                case Vehiculo::INCIDENTES:

                    break;
                default:

            }
        }else{
            // en caso de que vengan dos o varios que no sean todos se hará recursivo llamando la misma funcion obtenerHistorial

        }
    }
}

//21E32R43Caerf2234dvg