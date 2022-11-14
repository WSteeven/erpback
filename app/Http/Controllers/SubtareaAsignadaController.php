<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubtareaResource;
use App\Models\Subtarea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtareaAsignadaController extends Controller
{
    // Trabajo asignado
    public function trabajoAsignado()
    {
        // $estado = request('estado');
        $grupo = Auth::user()->empleado->grupo_id;

        return SubtareaResource::collection(Subtarea::filter()->where('grupo_id', $grupo)->simplePaginate());

        /*$filter = Subtarea::filter()->get();
        SubtareaResource::collection($filter);*/
        // return $filter;
    }

    public function pausar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::PAUSADO;
        // $subtarea->fecha_hora_pa = Carbon::now();
        $subtarea->save();
    }

    public function reanudar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::EJECUTANDO;
        // $subtarea->fecha_hora_pa = Carbon::now();
        $subtarea->save();
    }
}
