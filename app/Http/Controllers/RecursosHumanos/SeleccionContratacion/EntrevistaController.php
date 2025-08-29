<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\EntrevistaRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\EntrevistaResource;
use App\Mail\RecursosHumanos\SeleccionContratacion\NotificarEntrevistaMail;
use App\Mail\RecursosHumanos\SeleccionContratacion\NotificarEntrevistaReagendadaMail;
use App\Models\RecursosHumanos\SeleccionContratacion\Entrevista;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class EntrevistaController extends Controller
{

    public function __construct()
    {
        $this->middleware('check.user.logged.in');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EntrevistaRequest $request
     * @return JsonResponse
     * @throws ValidationException|Throwable
     */
    public function store(EntrevistaRequest $request)
    {
        $datos = $request->validated();
//        Log::channel('testing')->info('Log', ['store::entrevista', $request->all(), $datos]);
        try {
            DB::beginTransaction();
            $postulacion = Postulacion::find($datos['postulacion_id']);
            $postulacion->estado = Postulacion::ENTREVISTA;
            $postulacion->save();
            $entrevista = Entrevista::create($datos);
            Mail::to($postulacion->user->email)->send(new NotificarEntrevistaMail($postulacion, $entrevista));
            $modelo = new EntrevistaResource($entrevista);
            $mensaje = "¡Entrevista agendada exitosamente!";
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
     * @param Entrevista $entrevista
     * @return JsonResponse
     */
    public function show(Entrevista $entrevista)
    {
        $modelo = new EntrevistaResource($entrevista);

        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EntrevistaRequest $request
     * @param Entrevista $entrevista
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(EntrevistaRequest $request, Entrevista $entrevista)
    {
        $datos = $request->validated();
        $reagendadaPreviamente = $entrevista->reagendada;
        $postulacion = Postulacion::find($datos['postulacion_id']);
        try {
            DB::beginTransaction();
            $entrevista->update($datos);
            $modelo = new EntrevistaResource($entrevista->refresh());
            if (!$reagendadaPreviamente && $entrevista->reagendada) // Se envia mail indicando el nuevo horario
                Mail::to($postulacion->user->email)->send(new NotificarEntrevistaReagendadaMail($postulacion, $entrevista));
            $mensaje = "¡Entrevista actualizada exitosamente!";
            DB::commit();
        } catch (Throwable $th) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }


}
