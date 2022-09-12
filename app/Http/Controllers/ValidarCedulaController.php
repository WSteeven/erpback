<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Src\Shared\ValidarIdentificacion;

class ValidarCedulaController extends Controller
{
    public function validarCedula(Request $request)
    {
        $validador = new ValidarIdentificacion();
        if ($validador->validarCedula($request->cedula)) {
            return response()->json('Cedula valida');
        } else {
            return response()->json('Cedula incorrecta: ' . $validador->getError());
        }
    }

    public function validarRUC(Request $request)
    {        
        $existeRUC = Http::timeout(3)->get('https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=' . $request->cedula);
        return response()->json(['RUC válido' => $existeRUC->body()]);
    }
}
