<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRealizadaRequest;
use App\Models\ActividadRealizada;
use Illuminate\Http\Request;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;

class ActividadRealizadaController extends Controller
{
    public function index(){
        $results = ActividadRealizada::filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ActividadRealizadaRequest $request){
        $datos = $request->validated();

    }
}
