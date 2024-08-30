<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\ExamenRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\ExamenResource;
use App\Mail\RecursosHumanos\SeleccionContratacion\NotificarAgendamientoExamenesMail;
use App\Mail\RecursosHumanos\SeleccionContratacion\NotificarPostulanteContratado;
use App\Mail\RecursosHumanos\SeleccionContratacion\PostulacionDescartadaMail;
use App\Models\RecursosHumanos\SeleccionContratacion\Examen;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\SeleccionContratacion\PostulacionService;
use Src\Shared\Utils;
use Throwable;

class ExamenController extends Controller
{
    private string $entidad = 'Examen';
    private PostulacionService $postulacionService;

    public function __construct()
    {
        $this->postulacionService = new PostulacionService();
        $this->middleware('check.user.logged.in');
        $this->middleware('can:puede.crear.rrhh_examenes_postulantes')->only('store');
        $this->middleware('can:puede.editar.rrhh_examenes_postulantes')->only('update');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ExamenRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(ExamenRequest $request)
    {
        $datos = $request->validated();
//        Log::channel('testing')->info('Log', ['store::examen', $request->all(), $datos]);
        try {
            DB::beginTransaction();
            $postulacion = Postulacion::find($datos['postulacion_id']);
            $postulacion->estado = Postulacion::EXAMENES_MEDICOS;
            $postulacion->save();
            $examen = Examen::create($datos);
            Mail::to($postulacion->user->email)->send(new NotificarAgendamientoExamenesMail($postulacion, $examen));
            $modelo = new ExamenResource($examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Examen $examen
     * @return JsonResponse
     */
    public function show(Examen $examen)
    {
        $modelo = new ExamenResource($examen);

        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExamenRequest $request
     * @param Examen $examen
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(ExamenRequest $request, Examen $examen)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();
            $examen->update($datos);
            if($examen->se_realizo_examen && $examen->es_apto){
                // se actualiza la postulacion a contrado y se notifica al postulante
                $examen->postulacion()->update(['estado'=>Postulacion::CONTRATADO]);
                Mail::to($examen->postulacion->user->email)->send(new NotificarPostulanteContratado($examen->postulacion));
                // también se actualiza la vacante y se cierra las demás postulaciones para esta vacante
                $this->postulacionService->actualizarVacante($examen->postulacion);
            }else{
                $examen->postulacion()->update(['estado'=>Postulacion::DESCARTADO]);
                Mail::to($examen->postulacion->user->email)->send(new PostulacionDescartadaMail($examen->postulacion, false));
            }

            $modelo = new ExamenResource($examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }


}
