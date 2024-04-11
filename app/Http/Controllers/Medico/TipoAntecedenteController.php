<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoAntecedenteRequest;
use App\Http\Resources\Medico\TipoAntecedenteResource;
use App\Models\Medico\TipoAntecedente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoAntecedenteController extends Controller
{
    private $entidad = 'Tipo de antecedente de examen';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_antecedentes')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_antecedentes')->only('store');
        $this->middleware('can:puede.editar.tipos_antecedentes')->only('update');
        $this->middleware('can:puede.eliminar.tipos_antecedentes')->only('destroy');
    }

    public function index()
    {
        $results = TipoAntecedente::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoAntecedenteRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_antecedente = TipoAntecedente::create($datos);
            $modelo = new TipoAntecedenteResource($tipo_antecedente);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de antecedente' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoAntecedenteRequest $request, TipoAntecedente $tipo_antecedente)
    {
        $modelo = new TipoAntecedenteResource($tipo_antecedente);
        return response()->json(compact('modelo'));
    }


    public function update(TipoAntecedenteRequest $request, TipoAntecedente $tipo_antecedente)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_antecedente->update($datos);
            $modelo = new TipoAntecedenteResource($tipo_antecedente->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de antecedente' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoAntecedenteRequest $request, TipoAntecedente $tipo_antecedente)
    {
        try {
            DB::beginTransaction();
            $tipo_antecedente->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de antecedente' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
