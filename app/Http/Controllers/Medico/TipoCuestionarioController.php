<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoCuestionarioRequest;
use App\Http\Resources\Medico\TipoCuestionarioResource;
use App\Models\Medico\TipoCuestionario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoCuestionarioController extends Controller
{
    private $entidad = 'Tipo de Cuestionario';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_cuestionarios')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_cuestionarios')->only('store');
        $this->middleware('can:puede.editar.tipos_cuestionarios')->only('update');
        $this->middleware('can:puede.eliminar.tipos_cuestionarios')->only('destroy');

    }
    public function index()
    {
        $results = [];
        $results = TipoCuestionario::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoCuestionarioRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipocuestionario = TipoCuestionario::create($datos);
            $modelo = new TipoCuestionarioResource($tipocuestionario);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipocuestionario' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoCuestionarioRequest $request, TipoCuestionario $tipocuestionario)
    {
        $modelo = new TipoCuestionarioResource($tipocuestionario);
        return response()->json(compact('modelo'));
    }


    public function update(TipoCuestionarioRequest $request, TipoCuestionario $tipocuestionario)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipocuestionario->update($datos);
            $modelo = new TipoCuestionarioResource($tipocuestionario->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipocuestionario' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoCuestionarioRequest $request, TipoCuestionario $tipocuestionario)
    {
        try {
            DB::beginTransaction();
            $tipocuestionario->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipocuestionario' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
