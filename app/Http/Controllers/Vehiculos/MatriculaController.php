<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\MatriculaRequest;
use App\Http\Resources\Vehiculos\MatriculaResource;
use App\Models\Vehiculos\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class MatriculaController extends Controller
{
    private $entidad = 'Matricula';
    public function __construct()
    {
        $this->middleware('can:puede.ver.matriculas_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.matriculas_vehiculos')->only('store');
        $this->middleware('can:puede.editar.matriculas_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.matriculas_vehiculos')->only('destroy');
    }

    public function index()
    {
        $results = Matricula::filter()->get();
        $results = MatriculaResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(MatriculaRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['vehiculo_id'] = $request->safe()->only('vehiculo')['vehiculo'];

            $matricula = Matricula::create($datos);
            $modelo = new MatriculaResource($matricula);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al registrar la matrícula: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
            return response()->json(compact('mensaje'), 500);
        }
    }

    public function show(Matricula $matricula)
    {
        $modelo = new MatriculaResource($matricula);
        return response()->json(compact('modelo'));
    }

    // public function update(MatriculaRequest $request,Matricula $matricula)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $datos = $request->validated();
    //         $datos['vehiculo_id'] = $request->safe()->only('vehiculo')['vehiculo'];

    //         $matricula->update($datos);
    //         $modelo = new MatriculaResource($matricula);
    //         $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
    //         DB::commit();
    //         return response()->json(compact('mensaje', 'modelo'));
    //     } catch (\Exception $e) {
    //         $mensaje = '(' . $e->getLine() . ') Hubo un error al actualizar el registro de la matrícula: ' . $e->getMessage();
    //         throw ValidationException::withMessages([
    //             '500' => [$mensaje],
    //         ]);
    //         return response()->json(compact('mensaje'), 500);
    //     }
    // }

    public function pagar(Request $request, Matricula $matricula)
    {
        $request->validate([
            'matriculador' => ['required', 'string'],
            'observacion' => ['nullable', 'string'],
            'monto' => ['nullable', 'sometimes', 'numeric'],
        ]);
        if (!$matricula->matriculado) {
            $matricula->matriculado = true;
            $matricula->matriculador = $request['matriculador'];
            $matricula->observacion = $request['observacion'];
            $matricula->monto = $request['monto'];
            $matricula->save();
        }
        $matricula->latestNotificacion()->update(['leida' => true]);
        $modelo = new MatriculaResource($matricula->refresh());
        $mensaje = 'Matricula pagada correctamente';
        return response()->json(compact('modelo', 'mensaje'));
    }
}
