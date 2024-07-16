<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoExamenRequest;
use App\Http\Resources\Medico\TipoExamenResource;
use App\Models\Medico\TipoExamen;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoExamenController extends Controller
{
    private $entidad = 'Tipo de examen';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_examenes')->only('store');
        $this->middleware('can:puede.editar.tipos_examenes')->only('update');
        $this->middleware('can:puede.eliminar.tipos_examenes')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = TipoExamen::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_examen = TipoExamen::create($datos);
            $modelo = new TipoExamenResource($tipo_examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoExamenRequest $request, TipoExamen $tipo_examen)
    {
        $modelo = new TipoExamenResource($tipo_examen);
        return response()->json(compact('modelo'));
    }


    public function update(TipoExamenRequest $request, TipoExamen $tipo_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_examen->update($datos);
            $modelo = new TipoExamenResource($tipo_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoExamenRequest $request, TipoExamen $tipo_examen)
    {
        try {
            DB::beginTransaction();
            $tipo_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
