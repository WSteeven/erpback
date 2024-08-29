<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\EntrevistaRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\EntrevistaResource;
use App\Mail\RecursosHumanos\SeleccionContratacion\NotificarEntrevistaMail;
use App\Models\RecursosHumanos\SeleccionContratacion\Entrevista;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Tests\Models\Post;
use Throwable;

class EntrevistaController extends Controller
{
    private string $entidad = 'Entrevista';

    public function __construct()
    {
        $this->middleware('check.user.logged.in');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EntrevistaRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(EntrevistaRequest $request)
    {
        $datos = $request->validated();
        Log::channel('testing')->info('Log', ['store::entrevista', $request->all(), $datos]);
        try {
            DB::beginTransaction();
            $postulacion = Postulacion::find($datos['postulacion_id']);
            $postulacion->estado = Postulacion::ENTREVISTA;
            $postulacion->save();
            $entrevista = Entrevista::create($datos);
            Mail::to($postulacion->user->email)->send(new NotificarEntrevistaMail($postulacion, $entrevista));
//            throw new \Exception("error controlado");
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
     * @param Request $request
     * @param int $id
     * @return Response
     */
//    public function update(Request $request, Entrevista $entrevista)
//    {
//
//    }


}
