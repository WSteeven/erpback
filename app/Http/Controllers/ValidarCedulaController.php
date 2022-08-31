<?php

namespace App\Http\Controllers;

use App\Validadores\ValidarIdentificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function validarRUCPNatural(Request $request)
    {
        /* $validador = new ValidarIdentificacion();
        if ($validador->validarRucPersonaNatural($request->cedula)) {
            return response()->json(['RUC válido' => $validador->validarRucPersonaNatural($request->cedula)]);
        } else {
            return response()->json('RUC incorrecto: ' . $validador->getError());
        } */
        //return response()->json(strlen($request->cedula));

        $existeRUC = Http::timeout(3)->get('https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=' . $request->cedula);
        if ($existeRUC->body() == 'true') {
            return response()->json(['RUC válido' => $existeRUC->body()]);
        } else {
            return response()->json(['RUC inválido' => $existeRUC->body()]);
        }
    }

    public function validarRUCSPrivada(Request $request)
    {
        $validador = new ValidarIdentificacion();
        if ($validador->validarRucSociedadPrivada($request->cedula)) {
            return response()->json('RUC válido');
        } else {
            return response()->json('RUC incorrecto: ' . $validador->getError());
        }
    }

    public function validarRUCSPublica(Request $request)
    {
        $validador = new ValidarIdentificacion();
        if ($validador->validarRucSociedadPublica($request->cedula)) {
            return response()->json('RUC válido');
        } else {
            return response()->json('RUC incorrecto: ' . $validador->getError());
        }
    }
}
