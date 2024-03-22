<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ConsultaMedicaRequest;
use App\Http\Requests\Medico\ConsultaRequest;
use App\Http\Resources\Medico\ConsultaMedicaResource;
use App\Http\Resources\Medico\ConsultaResource;
use App\Models\Medico\Consulta;
use App\Models\Medico\ConsultaMedica;
use App\Models\Medico\Diagnostico;
use App\Models\Medico\DiagnosticoCita;
use App\Models\Medico\DiagnosticoCitaMedica;
use App\Models\Medico\Receta;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ConsultaMedicaController extends Controller
{
    private $entidad = 'Consulta médica';

    public function __construct()
    {
        $this->middleware('can:puede.ver.consultas_medicas')->only('index', 'show');
        $this->middleware('can:puede.crear.consultas_medicas')->only('store');
        $this->middleware('can:puede.editar.consultas_medicas')->only('update');
        $this->middleware('can:puede.eliminar.consultas_medicas')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = ConsultaMedica::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = ConsultaMedicaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ConsultaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConsultaMedicaRequest $request)
    {
        try {
            $datos = $request->validated();

            DB::beginTransaction();

            $datos = $request->validated();
            $consulta_medica = ConsultaMedica::create([
                'observacion' => $datos['observacion'],
                'cita_medica_id' => $datos['cita_medica'],
                'registro_empleado_examen_id' => isset($datos['registro_empleado_examen']) ? $datos['registro_empleado_examen'] : null,
            ]);

            $consulta_medica->receta()->create([
                'rp' => $datos['receta']['rp'],
                'prescripcion' => $datos['receta']['prescripcion'],
            ]);

            Log::channel('testing')->info('Log', ['diagn', $datos]);

            foreach ($datos['diagnosticos'] as $diagnostico) {
                Log::channel('testing')->info('Log', ['diagn', $diagnostico]);
                $consulta_medica->diagnosticosCitaMedica()->create([
                    'recomendacion' => $diagnostico['recomendacion'],
                    'cie_id' => $diagnostico['cie'],
                    // 'consulta_id' => $consulta_medica->id,
                    // 'cita_medica_id' => $datos['cita_medica'],
                    // 'registro_empleado_examen_id' => $datos['registro_empleado_examen'],
                ]);
            }

            $modelo = new ConsultaMedicaResource($consulta_medica);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar ' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /*public function update(ConsultaMedicaRequest $request, ConsultaMedica $consulta_medica)
    {
        try {
            $datos = $request->validated();

            DB::beginTransaction();

            $datos = $request->validated();
            // Log::channel('testing')->info('Log', ['diagn', 'diagnosticos gerger']);
            $consulta_medica->update([
                'observacion' => $datos['observacion'],
                'cita_medica_id' => $datos['cita_medica'],
                'registro_empleado_examen_id' => $datos['registro_empleado_examen'],
            ]);

            $consulta_medica->receta->update([
                'rp' => $datos['rp'],
                'prescripcion' => $datos['prescripcion'],
                'consulta_id' => $consulta_medica->id,
            ]);


            foreach ($datos['diagnosticos'] as $diagnostico) {
                DiagnosticoCitaMedica::create([
                    'recomendacion' => $diagnostico['recomendacion'],
                    'cie_id' => $diagnostico['cie'],
                    'consulta_id' => $consulta->id,
                    // 'cita_medica_id' => $datos['cita_medica'],
                    // 'registro_empleado_examen_id' => $datos['registro_empleado_examen'],
                ]);
            }

            $modelo = new ConsultaResource($receta);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar ' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }*/
    /*public function store(ConsultaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            $consulta = Consulta::create($datos);

            foreach($request['diagnosticos'] as $diagnostico) {
                $diagnostico['cie_id'] = $diagnostico['id'];

                DiagnosticoCitaMedica::create($diagnostico);
            }

            $modelo = new ConsultaResource($consulta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }*/

    /**
     * Display the specified resource.
     *
     * @param  Consulta  $consulta
     * @return \Illuminate\Http\Response
     */
    public function show(ConsultaMedica $consulta_medica)
    {
        $modelo = new ConsultaMedicaResource($consulta_medica);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ConsultaRequest  $request
     * @param  Consulta  $consulta
     * @return \Illuminate\Http\Response
     */
    /* public function update(ConsultaMedicaRequest $request, $consulta)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $consulta->update($datos);
            $modelo = new ConsultaResource($consulta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de consulta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    } */

    /**
     * Remove the specified resource from storage.
     *
     * @param  consulta  $consulta
     * @return \Illuminate\Http\Response
     */
    public function destroy($consulta)
    {
        try {
            DB::beginTransaction();
            $consulta->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de consulta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
