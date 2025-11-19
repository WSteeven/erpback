<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\ComentarioTicketRequest;
use App\Http\Resources\Tickets\ComentarioTicketResource;
use App\Models\Tickets\ComentarioTicket;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class ComentarioTicketController extends Controller
{
    private string $entidad = 'Comentario';

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = ComentarioTicket::filter()->orderBy('created_at')->get();
        $results = ComentarioTicketResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ComentarioTicketRequest $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(ComentarioTicketRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $modelo = ComentarioTicket::create($datos);
            $modelo = new ComentarioTicketResource($modelo);
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

    /**
     * @throws Throwable
     */
    public function subirArchivoComentarioTicket(Request $request)
    {
        $success = true;
        $comentario=ComentarioTicket::find($request->comentario_id);
        $modelo = ArchivoService::guardarArchivo($comentario, $request->file('archivo'), RutasStorage::ARCHIVOS_COMENTARIOS_TICKETS->value . '/' . Carbon::now()->format('Y-m'), ComentarioTicket::ADJUNTO_COMENTARIO_TICKET);

        $nuevoAdjunto = [
            'url'=>$modelo->ruta,
            'tipo'=>$request->file('archivo')->getMimeType(),
            'nombre'=>$modelo->nombre,
        ];
        $adjuntosActuales = is_array($comentario->adjuntos) ?$comentario->adjuntos: [];
        $adjuntosActuales[] = $nuevoAdjunto;

        $comentario->adjuntos =$adjuntosActuales;
        $comentario->save();
        return response()->json(compact('success', 'modelo'));
    }


}
