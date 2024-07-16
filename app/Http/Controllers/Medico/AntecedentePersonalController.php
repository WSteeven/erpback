<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\AntecedentePersonalRequest;
use App\Http\Resources\Medico\AntecedentePersonalResource;
use App\Models\Medico\AntecedentePersonal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class AntecedentePersonalController extends Controller
{
    private $entidad = 'Antecedentes Personales';

    public function __construct()
    {
        $this->middleware('can:puede.ver.antecedentes_personales')->only('index', 'show');
        $this->middleware('can:puede.crear.antecedentes_personales')->only('store');
        $this->middleware('can:puede.editar.antecedentes_personales')->only('update');
        $this->middleware('can:puede.eliminar.antecedentes_personales')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = AntecedentePersonal::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(AntecedentePersonalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $antecedente_personal = AntecedentePersonal::create($datos);
            $modelo = new AntecedentePersonalResource($antecedente_personal);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de antecedente personal' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(AntecedentePersonalRequest $request, AntecedentePersonal $antecedente_personal)
    {
        $modelo = new AntecedentePersonalResource($antecedente_personal);
        return response()->json(compact('modelo'));
    }


    public function update(AntecedentePersonalRequest $request, AntecedentePersonal $antecedente_personal)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $antecedente_personal->update($datos);
            $modelo = new AntecedentePersonalResource($antecedente_personal->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de antecedente personal' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(AntecedentePersonalRequest $request, AntecedentePersonal $antecedente_personal)
    {
        try {
            DB::beginTransaction();
            $antecedente_personal->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de antecedente personal' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
