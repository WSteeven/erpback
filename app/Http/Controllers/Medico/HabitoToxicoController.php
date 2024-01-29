<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\HabitoToxicoRequest;
use App\Http\Resources\Medico\HabitoToxicoResource;
use App\Models\Medico\HabitoToxico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class HabitoToxicoController extends Controller
{
    private $entidad = 'Habito toxico';

    public function __construct()
    {
        $this->middleware('can:puede.ver.habitos_toxicos')->only('index', 'show');
        $this->middleware('can:puede.crear.habitos_toxicos')->only('store');
        $this->middleware('can:puede.editar.habitos_toxicos')->only('update');
        $this->middleware('can:puede.eliminar.habitos_toxicos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = HabitoToxico::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(HabitoToxicoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $identidad_genero = HabitoToxico::create($datos);
            $modelo = new HabitoToxicoResource($identidad_genero);
            $this->tabla_roles($identidad_genero);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de habito toxico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(HabitoToxicoRequest $request, HabitoToxico $identidad_genero)
    {
        $modelo = new HabitoToxicoResource($identidad_genero);
        return response()->json(compact('modelo'));
    }


    public function update(HabitoToxicoRequest $request, HabitoToxico $identidad_genero)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $identidad_genero->update($datos);
            $modelo = new HabitoToxicoResource($identidad_genero->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de habito toxico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(HabitoToxicoRequest $request, HabitoToxico $identidad_genero)
    {
        try {
            DB::beginTransaction();
            $identidad_genero->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de habito toxico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
