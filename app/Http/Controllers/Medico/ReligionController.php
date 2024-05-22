<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ReligionRequest;
use App\Http\Resources\Medico\ReligionResource;
use App\Models\Medico\Religion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ReligionController extends Controller
{
    private $entidad = 'Religion';

    public function __construct()
    {
        $this->middleware('can:puede.ver.religiones')->only('index', 'show');
        $this->middleware('can:puede.crear.religiones')->only('store');
        $this->middleware('can:puede.editar.religiones')->only('update');
        $this->middleware('can:puede.eliminar.religiones')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = Religion::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ReligionRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $religion = Religion::create($datos);
            $modelo = new ReligionResource($religion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de religion' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ReligionRequest $request, Religion $religion)
    {
        $modelo = new ReligionResource($religion);
        return response()->json(compact('modelo'));
    }


    public function update(ReligionRequest $request, Religion $religion)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $religion->update($datos);
            $modelo = new ReligionResource($religion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de religion' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ReligionRequest $request, Religion $religion)
    {
        try {
            DB::beginTransaction();
            $religion->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de religion' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
