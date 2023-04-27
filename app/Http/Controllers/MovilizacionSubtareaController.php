<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovilizacionSubtareaResource;
use App\Models\MovilizacionSubtarea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Utils;

class MovilizacionSubtareaController extends Controller
{
    private $entidad = 'Movimiento';

    public function index()
    {
        $results = MovilizacionSubtareaResource::collection(MovilizacionSubtarea::filter()->get());
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subtarea' => 'required|numeric|integer',
            'motivo' => 'required|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        $modelo = MovilizacionSubtarea::create([
            'fecha_hora_salida' => Carbon::now(), //Carbon::parse($request['fecha_hora_salida'])->format('Y-m-d'),
            'empleado_id' => Auth::user()->empleado->id,
            'latitud' => $request['latitud'],
            'longitud' => $request['longitud'],
            'subtarea_id' => $request['subtarea'],
            'motivo' => $request['motivo'],
        ]);

        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destinoActual()
    {
        $results = MovilizacionSubtareaResource::collection(MovilizacionSubtarea::filter()->where('fecha_hora_llegada', null)->orderBy('fecha_hora_salida', 'desc')->get());
        return response()->json(compact('results'));
    }
}
