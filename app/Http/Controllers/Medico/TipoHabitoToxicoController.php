<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoHabitoToxicoRequest;
use App\Http\Resources\Medico\TipoHabitoToxicoResource;
use App\Models\Medico\TipoHabitoToxico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoHabitoToxicoController extends Controller
{
    private $entidad = 'Tipo de habito toxico';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_habitos_toxicos')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_habitos_toxicos')->only('store');
        $this->middleware('can:puede.editar.tipos_habitos_toxicos')->only('update');
        $this->middleware('can:puede.eliminar.tipos_habitos_toxicos')->only('destroy');
    }

    public function index()
    {
        $results = TipoHabitoToxico::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoHabitoToxicoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_habito_toxico = TipoHabitoToxico::create($datos);
            $modelo = new TipoHabitoToxicoResource($tipo_habito_toxico);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de habito toxico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoHabitoToxicoRequest $request, TipoHabitoToxico $tipo_habito_toxico)
    {
        $modelo = new TipoHabitoToxicoResource($tipo_habito_toxico);
        return response()->json(compact('modelo'));
    }


    public function update(TipoHabitoToxicoRequest $request, TipoHabitoToxico $tipo_habito_toxico)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_habito_toxico->update($datos);
            $modelo = new TipoHabitoToxicoResource($tipo_habito_toxico->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de habito toxico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoHabitoToxicoRequest $request, TipoHabitoToxico $tipo_habito_toxico)
    {
        try {
            DB::beginTransaction();
            $tipo_habito_toxico->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de habito toxico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
