<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaPreocupacionalRequest;
use App\Http\Resources\Medico\FichaPreocupacionalResource;
use App\Models\Empleado;
use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\RegistroEmpleadoExamen;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\FichaPreocupacionalService;
use Src\Shared\Utils;

class FichaPreocupacionalController extends Controller
{
    private $entidad = 'FichaPreocupacional';

    public function __construct()
    {
        $this->middleware('can:puede.ver.fichas_periodicas_preocupacionales')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_periodicas_preocupacionales')->only('store');
        $this->middleware('can:puede.editar.fichas_periodicas_preocupacionales')->only('update');
        $this->middleware('can:puede.eliminar.fichas_periodicas_preocupacionales')->only('destroy');
    }

    public function index()
    {
        $results = FichaPreocupacional::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(FichaPreocupacionalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $ficha_preocupacional = FichaPreocupacional::create($datos);
            $ficha_preocupacional_service = new FichaPreocupacionalService($ficha_preocupacional);
            $ficha_preocupacional_service->guardarDatosFichaPreocupacional($request);
            
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new FichaPreocupacionalResource($ficha_preocupacional);
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getLine(), $e->getMessage()],
            ]);
        }
    }

    public function show(FichaPreocupacional $ficha_preocupacional)
    {
        $modelo = new FichaPreocupacionalResource($ficha_preocupacional);
        return response()->json(compact('modelo'));
    }


    public function update(FichaPreocupacionalRequest $request, FichaPreocupacional $ficha_preocupacional)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $ficha_preocupacional->update($datos);
            $ficha_preocupacional_service = new FichaPreocupacionalService($ficha_preocupacional);
            $ficha_preocupacional_service->actualizarDatosFichaPreocupacional($request);

            $modelo = new FichaPreocupacionalResource($ficha_preocupacional->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al actualizar registro' => [$e->getMessage()],
            ]);
        }
    }

    public function destroy(FichaPreocupacionalRequest $request, FichaPreocupacional $ficha_preocupacional)
    {
        try {
            DB::beginTransaction();
            $ficha_preocupacional->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de preocupacional' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
