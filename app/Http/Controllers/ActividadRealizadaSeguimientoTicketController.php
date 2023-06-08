<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRealizadaSeguimientoTicketRequest;
use App\Http\Resources\ActividadRealizadaSeguimientoTicketResource;
use App\Models\ActividadRealizadaSeguimientoTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class ActividadRealizadaSeguimientoTicketController extends Controller
{
    private $entidad = 'Actividad';

    public function index()
    {
        $results = ActividadRealizadaSeguimientoTicket::filter()->latest()->get();
        $results = ActividadRealizadaSeguimientoTicketResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(ActividadRealizadaSeguimientoTicketRequest $request)
    {
        $datos = $request->validated();
        $datos['ticket_id'] = $datos['ticket'];

        if ($datos['fotografia']) $datos['fotografia'] = (new GuardarImagenIndividual($datos['fotografia'], RutasStorage::FOTOGRAFIAS_SEGUIMIENTOS_TICKETS))->execute();

        $modelo = new ActividadRealizadaSeguimientoTicket();
        $datos['fecha_hora'] = Carbon::parse($datos['fecha_hora'])->format('Y-m-d H:i:s');
        $modelo->fill($datos);
        $modelo->save();

        $modelo = new ActividadRealizadaSeguimientoTicketResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }
}
