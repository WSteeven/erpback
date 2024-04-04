<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaAptitudRequest;
use App\Http\Resources\Medico\FichaAptitudResource;
use App\Models\Medico\FichaAptitud;
use App\Models\Medico\ProfesionalSalud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\FichaAptitudService;
use Src\Shared\Utils;

class FichaAptitudController extends Controller
{
    private $entidad = 'Examen Ficha de aptitud';

    public function __construct()
    {
        $this->middleware('can:puede.ver.fichas_aptitudes')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_aptitudes')->only('store');
        $this->middleware('can:puede.editar.fichas_aptitudes')->only('update');
        $this->middleware('can:puede.eliminar.fichas_aptitudes')->only('destroy');
    }

    public function index()
    {
        $results = FichaAptitud::ignoreRequest(['campos'])->filter()->get();
        $results = FichaAptitudResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(FichaAptitudRequest $request)
    {
        try {
            DB::beginTransaction();

            $datos = $request->validated();
            $ficha_aptitud = FichaAptitud::create($datos);

            // opcionesRespuestasTipoEvaluacionMedicaRetiro
            foreach ($datos['opciones_respuestas_tipo_evaluacion_medica_retiro'] as $opcion) {
                $opcion['tipo_evaluacion_medica_retiro_id'] = $opcion['tipo_evaluacion_medica_retiro'];
                $ficha_aptitud->opcionesRespuestasTipoEvaluacionMedicaRetiro()->create($opcion);
            }
            
            $modelo = new FichaAptitudResource($ficha_aptitud->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de ficha de aptitud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(FichaAptitud $ficha_aptitud)
    {
        $modelo = new FichaAptitudResource($ficha_aptitud);
        return response()->json(compact('modelo'));
    }


    public function update(FichaAptitudRequest $request, FichaAptitud $ficha_aptitud)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $ficha_aptitud->update($datos);
            $modelo = new FichaAptitudResource($ficha_aptitud->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de ficha de aptitud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(FichaAptitud $ficha_aptitud)
    {
        try {
            DB::beginTransaction();
            $ficha_aptitud->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de ficha de aptitud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
