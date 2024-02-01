<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\IdentidadGeneroRequest;
use App\Http\Resources\Medico\IdentidadGeneroResource;
use App\Models\Medico\IdentidadGenero;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class IdentidadGeneroController extends Controller
{
    private $entidad = 'Identidad de Genero';

    public function __construct()
    {
        $this->middleware('can:puede.ver.identidades_generos')->only('index', 'show');
        $this->middleware('can:puede.crear.identidades_generos')->only('store');
        $this->middleware('can:puede.editar.identidades_generos')->only('update');
        $this->middleware('can:puede.eliminar.identidades_generos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = IdentidadGenero::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(IdentidadGeneroRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $identidad_genero = IdentidadGenero::create($datos);
            $modelo = new IdentidadGeneroResource($identidad_genero);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de identidad de genero' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(IdentidadGeneroRequest $request, IdentidadGenero $identidad_genero)
    {
        $modelo = new IdentidadGeneroResource($identidad_genero);
        return response()->json(compact('modelo'));
    }


    public function update(IdentidadGeneroRequest $request, IdentidadGenero $identidad_genero)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $identidad_genero->update($datos);
            $modelo = new IdentidadGeneroResource($identidad_genero->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de identidad de genero' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(IdentidadGeneroRequest $request, IdentidadGenero $identidad_genero)
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
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de identidad de genero' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }}
