<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\BancoPostulanteRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\BancoPostulanteResource;
use App\Models\RecursosHumanos\SeleccionContratacion\BancoPostulante;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\SeleccionContratacion\PostulacionService;
use Src\Shared\Utils;
use Throwable;

class BancoPostulanteController extends Controller
{
    private string $entidad = 'Postulante';
    private PostulacionService $postulacionService;

    public function __construct()
    {
        $this->postulacionService = new PostulacionService();
        // Asegura que el usuario esté autenticado en todas las acciones
        $this->middleware('check.user.logged.in');

        $this->middleware('can:puede.ver.rrhh_bancos_postulantes')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_bancos_postulantes')->only('store');
        $this->middleware('can:puede.editar.rrhh_bancos_postulantes')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_bancos_postulantes')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = BancoPostulante::filter()->get();
        $results = BancoPostulanteResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BancoPostulanteRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(BancoPostulanteRequest $request)
    {
        $modelo = null;
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            // primero se busca y actualiza la postulacion
            if ($datos['postulacion_id'] > 0) {
                $postulacion = Postulacion::find($datos['postulacion_id']);
                $postulacion->estado = Postulacion::BANCO_DE_CANDIDATOS;
                $postulacion->save();
                // Notificamos al postulante que ya no continua el proceso y se agregó al banco de postulantes
                $this->postulacionService->notificarBancoPostulante($postulacion);
                // tomamos el user_id y el user_type de la postulacion para agregar en el registro del banco de postulante.
                // pero antes verificamos si el usuario ya está en banco de postulantes
                $estaEnBanco = $this->postulacionService->estaEnBanco($postulacion->user_id, $postulacion->user_type);
                if (!$estaEnBanco) { //si no está en banco lo agrega, caso contrario no sucede nada.
                    $datos['user_id'] = $postulacion->user_id;
                    $datos['user_type'] = $postulacion->user_type;
                    $banco = BancoPostulante::create($datos);
                    $modelo = new BancoPostulanteResource($banco);
                }
            }
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param BancoPostulante $banco
     * @return JsonResponse
     */
    public function show(BancoPostulante $banco)
    {
        $modelo = new BancoPostulanteResource($banco);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BancoPostulanteRequest $request
     * @param BancoPostulante $banco
     * @throws ValidationException
     */
//    public function update(BancoPostulanteRequest $request, BancoPostulante $banco)
//    {
//        try {
//            throw new Exception('Metodo no configurado aún');
//        } catch (Throwable $th) {
//            throw Utils::obtenerMensajeErrorLanzable($th);
//        }
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
//    public function destroy($id)
//    {
//        try {
//            throw new Exception('Metodo no configurado aún. Comunícate con Dept. Informático.');
//        } catch (Throwable $th) {
//            throw Utils::obtenerMensajeErrorLanzable($th);
//        }
//    }
}
