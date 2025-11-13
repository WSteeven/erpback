<?php

namespace App\Http\Controllers\Medico;

use App\Events\Medico\NotificarCitaMedicaADoctorEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\CitaMedicaRequest;
use App\Http\Resources\Medico\CitaMedicaResource;
use App\Models\Medico\CitaMedica;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\CitaMedicaService;
use Src\Shared\Utils;
use Throwable;

class CitaMedicaController extends Controller
{
    private string $entidad = 'Cita médica';
    private CitaMedicaService $citaMedicaService;

    public function __construct()
    {
        $this->middleware('can:puede.ver.citas_medicas')->only('index', 'show');
        $this->middleware('can:puede.crear.citas_medicas')->only('store');
        $this->middleware('can:puede.editar.citas_medicas')->only('update');
        $this->middleware('can:puede.eliminar.citas_medicas')->only('destroy');

        $this->citaMedicaService = new CitaMedicaService();
    }

    public function index()
    {
        $results = CitaMedica::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = CitaMedicaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(CitaMedicaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            $datos['estado_cita_medica'] = CitaMedica::PENDIENTE;

            $cita_medica = CitaMedica::create($datos);

            event(new NotificarCitaMedicaADoctorEvent($cita_medica));
            $modelo = new CitaMedicaResource($cita_medica);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    public function show(CitaMedica $cita_medica)
    {
        $modelo = new CitaMedicaResource($cita_medica);
        return response()->json(compact('modelo'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(CitaMedicaRequest $request, CitaMedica $cita_medica)
    {
        try {
            DB::beginTransaction();

            $datos = $request->validated();

            $keys = $request->keys();
            unset($keys['id']);

            if ($datos['estado_cita_medica'] === CitaMedica::CANCELADO) {
                array_push($keys, 'estado_cita_medica', 'fecha_hora_cancelado', 'motivo_cancelacion');
                $request['fecha_hora_cancelado'] = Carbon::now();
                $request['motivo_cancelacion'] = request('motivo_cancelacion');
            }

            if ($datos['estado_cita_medica'] === CitaMedica::RECHAZADO) {
                array_push($keys, 'estado_cita_medica', 'fecha_hora_rechazo', 'motivo_rechazo');
                $request['fecha_hora_rechazo'] = Carbon::now();
                $request['motivo_rechazo'] = request('motivo_rechazo');
            }

//            Log::channel('testing')->info('Log', ['keys', $keys]);
//            Log::channel('testing')->info('Log', ['request', $request]);

            $cita_medica->update($request->only($keys));

            // $cita_medica->update($datos);
            $modelo = new CitaMedicaResource($cita_medica->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function destroy(CitaMedica $cita_medica)
    {
        try {
            DB::beginTransaction();
            $cita_medica->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    /* public function cancelar(CitaMedica $cita_medica)
    {
        return $this->citaMedicaService->cancelar($cita_medica);
    } */

    public function rechazar(CitaMedica $cita_medica)
    {
        return $this->citaMedicaService->rechazar($cita_medica);
    }
}
